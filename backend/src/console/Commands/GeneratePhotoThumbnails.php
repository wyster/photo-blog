<?php

namespace Console\Commands;

use Closure;
use Core\Models\Photo;
use Core\Models\Thumbnail;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\Collection;
use Lib\ThumbnailsGenerator\Contracts\ThumbnailsGenerator;

/**
 * Class GeneratePhotoThumbnails.
 *
 * @property Filesystem fileSystem
 * @property ThumbnailsGenerator thumbnailsGenerator
 * @package Console\Commands
 */
class GeneratePhotoThumbnails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:photo_thumbnails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate photo thumbnails';

    /**
     * GeneratePhotoThumbnails constructor.
     *
     * @param Filesystem $fileSystem
     * @param ThumbnailsGenerator $thumbnailsGenerator
     */
    public function __construct(Filesystem $fileSystem, ThumbnailsGenerator $thumbnailsGenerator)
    {
        parent::__construct();

        $this->fileSystem = $fileSystem;
        $this->thumbnailsGenerator = $thumbnailsGenerator;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->eachPhoto(function (Photo $photo) {
            $this->comment(sprintf('Generating thumbnails for photo (ID:%s) ...', $photo->id));
            $this->deletePhotoThumbnails($photo);
            $this->generatePhotoThumbnails($photo);
        });
    }

    /**
     * Apply callback function on each photo.
     *
     * @param Closure $callback
     * @return void
     */
    public function eachPhoto(Closure $callback)
    {
        Photo::with('thumbnails')->chunk(100, function (Collection $photos) use ($callback) {
            $photos->map($callback);
        });
    }

    /**
     * Delete photo thumbnails
     *
     * @param Photo $photo
     */
    public function deletePhotoThumbnails(Photo $photo)
    {
        $photo->thumbnails->map(function (Thumbnail $thumbnail) use ($photo) {
            $photo->thumbnails()->detach($thumbnail->id);
            $thumbnail->delete();
            $this->fileSystem->delete($thumbnail->path);
        });
    }

    /**
     * Generate photo thumbnails.
     *
     * @param Photo $photo
     */
    public function generatePhotoThumbnails(Photo $photo)
    {
        $absolutePhotoFilePath = storage_path('app') . '/' . $photo->path;

        $metaData = $this->thumbnailsGenerator->generateThumbnails($absolutePhotoFilePath);

        foreach ($metaData as $metaDataItem) {
            $relativeThumbnailPath = str_replace(storage_path('app') . '/', '', $metaDataItem['path']);
            $thumbnails[] = [
                'path' => $relativeThumbnailPath,
                'relative_url' => $this->fileSystem->url($relativeThumbnailPath),
                'width' => $metaDataItem['width'],
                'height' => $metaDataItem['height'],
            ];
        }

        $photo->thumbnails()->createMany($thumbnails ?? []);
    }
}