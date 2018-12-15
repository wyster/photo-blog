<?php

namespace App\Dom\Entities;

use BadFunctionCallException;
use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

abstract class AbstractEntity implements Arrayable, JsonSerializable
{
    /**
     * AbstractEntity constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        $this->assertAttributes($attributes);
    }

    /**
     * @param array $attributes
     */
    protected function assertAttributes(array $attributes): void
    {
        throw new BadFunctionCallException('Not implemented');
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
        throw new BadFunctionCallException('Not implemented');
    }
}
