<?php

namespace App\ValueObjects;

/**
 * Class Coordinates.
 *
 * @package App\ValueObjects
 */
final class Coordinates
{
    /**
     * @var Latitude
     */
    private $latitude;

    /**
     * @var Longitude
     */
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
     * @return string
     */
    public function __toString(): string
    {
        return sprintf('%s %s', $this->getLatitude(), $this->getLongitude());
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
}
