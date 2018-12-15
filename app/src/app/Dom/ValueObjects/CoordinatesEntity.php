<?php

namespace App\Dom\ValueObjects;

/**
 * Class CoordinatesEntity.
 *
 * @package App\Dom\ValueObjects
 */
final class CoordinatesEntity
{
    /**
     * @var LatitudeEntity
     */
    private $latitude;

    /**
     * @var LongitudeEntity
     */
    private $longitude;

    /**
     * CoordinatesEntity constructor.
     *
     * @param LatitudeEntity $latitude
     * @param LongitudeEntity $longitude
     */
    public function __construct(LatitudeEntity $latitude, LongitudeEntity $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    /**
     * @inheritdoc
     */
    public function __toString(): string
    {
        return sprintf('%s %s', $this->getLatitude(), $this->getLongitude());
    }

    /**
     * @return LatitudeEntity
     */
    public function getLatitude(): LatitudeEntity
    {
        return $this->latitude;
    }

    /**
     * @return LongitudeEntity
     */
    public function getLongitude(): LongitudeEntity
    {
        return $this->longitude;
    }
}
