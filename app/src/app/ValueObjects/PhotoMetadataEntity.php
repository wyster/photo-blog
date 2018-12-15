<?php

namespace App\ValueObjects;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;

/**
 * Class PhotoMetadataEntity.
 *
 * @package App\ValueObjects
 */
final class PhotoMetadataEntity implements Arrayable
{
    /**
     * @var array
     */
    private $attributes;

    /**
     * PhotoMetadataEntity constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * @inheritdoc
     */
    public function __toString(): string
    {
        return (string) $this->getValue();
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return collect($this->toArray())->crossJoin(' / ');
    }

    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        return [
            'manufacturer' => $this->getManufacturer(),
            'model' => $this->getModel(),
            'exposure_time' => $this->getExposureTime(),
            'aperture' => $this->getAperture(),
            'iso' => $this->getIso(),
            'taken_at' => $this->getTakenAt(),
            'software' => $this->getSoftware(),
        ];
    }

    /**
     * @return string|null
     */
    public function getManufacturer(): ?string
    {
        return $this->metadata['ifd0.Make'] ?? $this->metadata['exif.MakerNote'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getModel(): ?string
    {
        return $this->metadata['ifd0.Model'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getExposureTime(): ?string
    {
        $raw = $this->metadata['exif.ExposureTime'] ?? null;

        if (!is_string($raw)) {
            return null;
        }

        [$numerator, $denominator] = explode('/', $raw);

        if (!is_numeric($numerator) || !is_numeric($denominator)) {
            return null;
        }

        $value = $denominator / $numerator;

        return '1/' . number_format($value, 0);
    }

    /**
     * @return string|null
     */
    public function getAperture(): ?string
    {
        $raw = $this->metadata['exif.FNumber'] ?? null;

        if (!is_string($raw)) {
            return null;
        }

        [$numerator, $denominator] = explode('/', $raw);

        if (!is_numeric($numerator) || !is_numeric($denominator)) {
            return null;
        }

        $value = $numerator / $denominator;

        return 'f/' . number_format($value, 1);
    }

    /**
     * @return string|null
     */
    public function getIso(): ?string
    {
        return $this->metadata['exif.ISOSpeedRatings'] ?? null;
    }

    /**
     * @return Carbon|null
     */
    public function getTakenAt(): ?string
    {
        $raw = $this->metadata['exif.DateTimeOriginal'] ?? null;

        if (!is_string($raw) && !is_numeric($raw)) {
            return null;
        }

        return new Carbon($raw);
    }

    /**
     * @return string|null
     */
    public function getSoftware(): ?string
    {
        return $this->metadata['ifd0.Software'] ?? null;
    }
}
