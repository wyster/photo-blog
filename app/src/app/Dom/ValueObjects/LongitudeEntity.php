<?php

namespace App\Dom\ValueObjects;

use InvalidArgumentException;

/**
 * Class LongitudeEntity.
 *
 * @package App\Dom\ValueObjects
 */
final class LongitudeEntity
{
    /**
     * @var float
     */
    private $value;

    /**
     * LongitudeEntity constructor.
     *
     * @param float $value
     */
    public function __construct(float $value)
    {
        $this->assertValue($value);

        $this->value = $value;
    }

    /**
     * @param $value
     * @return void
     * @throws InvalidArgumentException
     */
    private function assertValue(float $value): void
    {
        if ($value < -180 && $value > 180) {
            throw new InvalidArgumentException('Invalid longitude value.');
        }
    }

    /**
     * @inheritdoc
     */
    public function __toString(): string
    {
        return (string) $this->getValue();
    }

    /**
     * @return float
     */
    public function getValue(): float
    {
        return $this->value;
    }
}
