<?php

namespace App\Dom\ValueObjects;

use InvalidArgumentException;

/**
 * Class Latitude.
 *
 * @package App\Dom\ValueObjects
 */
final class Latitude
{
    /**
     * @var float
     */
    private $value;

    /**
     * Latitude constructor.
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
        if ($value < -90 && $value > 90) {
            throw new InvalidArgumentException('Invalid latitude value.');
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
