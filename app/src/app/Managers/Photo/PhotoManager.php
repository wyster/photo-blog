<?php

namespace App\Managers\Photo;

use App\Managers\Location\LocationManager;
use App\Models\Photo;
use App\Services\Image\Contracts\ImageProcessor;
use Illuminate\Contracts\Filesystem\Factory as Storage;
use Illuminate\Database\ConnectionInterface as Database;
use function App\Util\str_unique;

/**
 * Class PhotoManager.
 *
 * @package App\Managers\Photo
 */
class PhotoManager
{
    /**
     * @var Database
     */
    private $database;

    /**
     * @var Storage
     */
    private $storage;

    /**
     * @var LocationManager
     */
    private $locationManager;

    /**
     * @var ImageProcessor
     */
    private $imageProcessor;

    /**
     * @var PhotoValidator
     */
    private $validator;

    /**
     * PhotoManager constructor.
     *
     * @param Database $database
     * @param Storage $storage
     * @param LocationManager $locationManager
     * @param ImageProcessor $imageProcessor
     * @param PhotoValidator $validator
     */
    public function __construct(
        Database $database,
        Storage $storage,
        LocationManager $locationManager,
        ImageProcessor $imageProcessor,
        PhotoValidator $validator
    )
    {
        $this->database = $database;
        $this->storage = $storage;
        $this->locationManager = $locationManager;
        $this->imageProcessor = $imageProcessor;
        $this->validator = $validator;
    }

    /**
     * Create a photo.
     *
     * @param array $attributes
     * @return Photo
     */
    public function create(array $attributes = []): Photo
    {
        $attributes = $this->validator->validateForCreate($attributes);

        $attributes['path'] = $this->storage->put(sprintf('photos/%s', str_unique(20)), $attributes['file']);

        $photo = (new Photo)->fill($attributes);

        $this->imageProcessor->open($attributes['path']);
        $photo->avg_color = $this->imageProcessor->getAvgColor();
        $photo->metadata = $this->imageProcessor->getMetadata();
        $thumbnails = $this->imageProcessor->createThumbnails();
        $this->imageProcessor->close();

        $this->database->transaction(function () use ($photo, $attributes, $thumbnails) {
            if (isset($attributes['location'])) {
                $location = $this->locationManager->create($attributes['location']);
                $attributes['location_id'] = $location->id;
            }
            $photo->save();
            $photo->thumbnails()->detach();
            collect($thumbnails)->each(function (array $attributes) use ($photo) {
                $photo->thumbnails()->create($attributes);
            });
        });

        $photo->load('location', 'thumbnails');

        return $photo;
    }

    /**
     * Update a photo.
     *
     * @param Photo $photo
     * @param array $attributes
     */
    public function update(Photo $photo, array $attributes): void
    {
        $attributes = $this->validator->validateForUpdate($attributes);

        $this->database->transaction(function () use ($photo, $attributes) {
            $location = $this->locationManager->create($attributes['location']);
            $attributes['location_id'] = $location->id;
            $photo->fill($attributes);
            $photo->save();
        });

        $photo->load('location', 'thumbnails');
    }

    /**
     * Get a photo by ID.
     *
     * @param int $id
     * @return Photo
     */
    public function getById(int $id): Photo
    {
        $photo = (new Photo)
            ->newQuery()
            ->findOrFail($id);

        return $photo;
    }

    /**
     * Delete a photo.
     *
     * @param Photo $photo
     * @return void
     */
    public function delete(Photo $photo): void
    {
        $this->database->transaction(function () use ($photo) {
            $photo->delete();
            $this->storage->deleteDirectory(pathinfo($photo->path, PATHINFO_DIRNAME));
        });
    }
}
