<?php

namespace App\Dom\Entities;

use InvalidArgumentException;

/**
 * Class TagEntity.
 *
 * @package App\Dom\Entities
 */
final class TagEntity extends AbstractEntity
{
    /**
     * @var string
     */
    private $value;

    /**
     * TagEntity constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        parent::__construct($attributes);

        $this->assertAttributes($attributes);

        $this->value = $attributes['value'];
    }

    /**
     * @inheritdoc
     */
    protected function assertAttributes(array $attributes): void
    {
        if (!isset($attributes['value']) || !is_string($attributes['value'])) {
            throw new InvalidArgumentException('Invalid tag value.');
        }
    }

    /**
     * @inheritdoc
     */
    public function __toString(): string
    {
        return $this->getValue();
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
            'value' => $this->getValue(),
        ];
    }
}
