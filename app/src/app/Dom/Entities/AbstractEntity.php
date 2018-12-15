<?php

namespace App\Dom\Entities;

use Exception;
use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

abstract class AbstractEntity implements Arrayable, JsonSerializable
{
    /**
     * AbstractEntity constructor.
     *
     * @param array $attributes
     * @throws Exception
     */
    public function __construct(array $attributes)
    {
        $this->assertAttributes($attributes);
    }

    /**
     * @param array $attributes
     * @throws Exception
     */
    protected function assertAttributes(array $attributes): void
    {
        throw new Exception('Not implemented.');
    }

    /**
     * Create a new instance from array of attributes.
     *
     * @param array $attributes
     * @return mixed
     */
    public static function fromArray(array $attributes)
    {
        return new static($attributes);
    }

    /**
     * @return mixed
     */
    public function toValue()
    {
        throw new Exception('Not implemented.');
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        throw new Exception('Not implemented.');
    }

    /**
     * @inheritdoc
     */
    public function __toString(): string
    {
        return (string) $this->getValue();
    }
}
