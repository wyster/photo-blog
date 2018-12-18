<?php

namespace Core\ValueObjects;

use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

/**
 * Class Coordinates.
 *
 * @package Core\ValueObjects
 */
final class Coordinates implements Arrayable, JsonSerializable
{
    private $latitude;
    private $longitude;

    /**
     * Coordinates constructor.
     *
     * @param Latitude $latitude
     * @param Longitude $longitude
     */
    public function __construct(Latitude $latitude, Longitude $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    /**
     * @return Latitude
     */
    public function getLatitude(): Latitude
    {
        return $this->latitude;
    }

    /**
     * @return Longitude
     */
    public function getLongitude(): Longitude
    {
        return $this->longitude;
    }

    /**
     * @inheritdoc
     */
    public function __toString(): string
    {
        return sprintf('%s %s', $this->getLatitude(), $this->getLongitude());
    }

    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        return [
            'latitude' => (float) $this->getLatitude(),
            'longitude' => (float) $this->getLongitude(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
