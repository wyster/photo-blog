<?php

namespace App\Dom\ValueObjects;

use Illuminate\Contracts\Support\Arrayable;
use InvalidArgumentException;

/**
 * Class TagEntity.
 *
 * @package App\Dom\ValueObjects
 */
final class TagEntity implements Arrayable
{
    /**
     * @var string
     */
    private $value;

    /**
     * TagEntity constructor.
     *
     * @param string $value
     */
    public function __construct(string $value)
    {
        $this->assertValue($value);

        $this->value = $value;
    }

    /**
     * @param $value
     * @return void
     * @throws InvalidArgumentException
     */
    private function assertValue(string $value): void
    {
        if (!is_string($value)) {
            throw new InvalidArgumentException('Invalid tag value.');
        }
    }

    /**
     * @return string
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
        return $this->value;
    }

    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        return [
            'value' => $this->value,
        ];
    }
}
