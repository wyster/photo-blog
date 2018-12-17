<?php

namespace Core\Entities;

use Core\ValueObjects\Coordinates;

/**
 * Class LocationEntity.
 *
 * @package Core\Entities
 */
final class LocationEntity extends AbstractEntity
{
    private $id;
    private $coordinates;

    /**
     * LocationEntity constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        $this->setId($attributes['id'] ?? null);
        $this->setCoordinates($attributes['coordinates'] ?? null);;
    }

    /**
     * @param int $id
     * @return LocationEntity
     */
    private function setId(int $id): LocationEntity
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param Coordinates $coordinates
     * @return LocationEntity
     */
    private function setCoordinates(Coordinates $coordinates): LocationEntity
    {
        $this->coordinates = $coordinates;

        return $this;
    }

    /**
     * @return Coordinates
     */
    public function getCoordinates(): Coordinates
    {
        return $this->coordinates;
    }

    /**
     * @inheritdoc
     */
    public function __toString(): string
    {
        return (string) $this->getCoordinates();
    }

    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'coordinates' => $this->getCoordinates(),
        ];
    }
}
