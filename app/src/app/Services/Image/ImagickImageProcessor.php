<?php

namespace App\Services\Image;

use App\Services\Image\Contracts\ImageProcessor;
use Illuminate\Contracts\Filesystem\Factory as Storage;
use Illuminate\Contracts\Validation\Factory as ValidatorFactory;
use Illuminate\Validation\Rule;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Image\Metadata\ExifMetadataReader;
use Imagine\Image\Point;
use Imagine\Imagick\Imagine;
use InvalidArgumentException;

/**
 * Class ImagickImageProcessor.
 *
 * @package App\Services\Image
 */
class ImagickImageProcessor implements ImageProcessor
{
    /**
     * @var Storage
     */
    private $storage;

    /**
     * @var array
     */
    private $config;

    /**
     * @var ImageInterface|null
     */
    private $image = null;

    /**
     * @var string|null
     */
    private $path = null;

    /**
     * ImagickImageProcessor constructor.
     *
     * @param Storage $storage
     * @param ValidatorFactory $validatorFactory
     * @param array $config
     */
    public function __construct(Storage $storage, ValidatorFactory $validatorFactory, array $config)
    {
        $validator = $validatorFactory->make($config, [
            'thumbnails' => ['required', 'array'],
            'thumbnails.*.mode' => ['required', Rule::in(['inset', 'outbound'])],
            'thumbnails.*.quality' => ['required', 'integer', 'min:0', 'max:100'],
            'thumbnails.*.prefix' => ['required', 'string', 'min:1'],
            'thumbnails.*.width' => ['required', 'integer', 'min:1'],
            'thumbnails.*.height' => ['required', 'integer', 'min:1'],
        ]);

        if ($validator->fails()) {
            throw new InvalidArgumentException('Invalid configuration has been provided.');
        }

        $this->storage = $storage;
        $this->config = $config;
    }

    /**
     * @inheritdoc
     */
    public function open(string $path): ImageProcessor
    {
        $this->close();

        $this->path = $path;

        $this->image = (new Imagine)
            ->setMetadataReader(new ExifMetadataReader)
            ->open($this->storage->path($path));

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function close(): ImageProcessor
    {
        $this->image = null;
        $this->path = null;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getAvgColor(): string
    {
        return (string) $this->image
            ->copy()
            ->resize(new Box(1, 1))
            ->getColorAt(new Point(0, 0));
    }

    /**
     * @inheritdoc
     */
    public function getMetadata(): array
    {
        return $this->image
            ->metadata()
            ->toArray();
    }

    /**
     * @inheritdoc
     */
    public function createThumbnails(): array
    {
        return collect($this->config['thumbnails'])
            ->map(function ($config) {
                $this->image
                    ->thumbnail(new Box($config['width'], $config['height']), $config['mode'])
                    ->save($this->getThumbnailStoragePath($config['prefix']), ['quality' => $config['quality']]);
                return [
                    'path' => $this->getThumbnailAbsolutePath($config['prefix']),
                    'width' => $config['width'],
                    'height' => $config['height'],
                ];
            })
            ->toArray();
    }

    /**
     * Get storage path to the thumbnail file.
     *
     * @param string|null $prefix
     * @return string
     */
    private function getThumbnailStoragePath(?string $prefix): string
    {
        return pathinfo($this->storage->path($this->path), PATHINFO_DIRNAME) . DIRECTORY_SEPARATOR . $this->getThumbnailName($prefix);
    }

    /**
     * Get fully specified path to the thumbnail file.
     *
     * @param string|null $prefix
     * @return string
     */
    private function getThumbnailAbsolutePath(?string $prefix): string
    {
        return pathinfo($this->path, PATHINFO_DIRNAME) . DIRECTORY_SEPARATOR . $this->getThumbnailName($prefix);
    }

    /**
     * Get thumbnail file name.
     *
     * @param string $prefix
     * @return string
     */
    private function getThumbnailName(string $prefix = 'thumbnail'): string
    {
        $fileName = pathinfo($this->storage->path($this->path), PATHINFO_FILENAME);
        $fileExtension = pathinfo($this->storage->path($this->path), PATHINFO_EXTENSION);
        return $fileName . '_' . $prefix . '.' . $fileExtension;
    }
}
