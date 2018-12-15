<?php

namespace App\Dom\ValueObjects;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use InvalidArgumentException;

/**
 * Class UserEntity.
 *
 * @package App\Dom\ValueObjects
 */
final class UserEntity implements Arrayable
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $role;

    /**
     * @var Carbon
     */
    private $createdAt;

    /**
     * @var Carbon
     */
    private $updatedAt;

    /**
     * UserEntity constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        $this->assertAttributes($attributes);

        $this->id = $attributes['id'];
        $this->name = $attributes['name'];
        $this->email = $attributes['email'];
        $this->role = $attributes['role'];
        $this->createdAt = $attributes['created_at'];
        $this->updatedAt = $attributes['updated_at'];
    }

    /**
     * @param $attributes
     * @return void
     * @throws InvalidArgumentException
     */
    private function assertAttributes(array $attributes): void
    {
        if (!isset($attributes['id']) || !is_int($attributes['id'])) {
            throw new InvalidArgumentException('Invalid id value.');
        }

        if (!isset($attributes['name']) || !is_string($attributes['name'])) {
            throw new InvalidArgumentException('Invalid name value.');
        }

        if (!isset($attributes['email']) || !is_string($attributes['email'])) {
            throw new InvalidArgumentException('Invalid email value.');
        }

        if (!isset($attributes['role']) || !is_string($attributes['role'])) {
            throw new InvalidArgumentException('Invalid role value.');
        }

        if (!isset($attributes['created_at']) || !($attributes['created_at'] instanceof Carbon)) {
            throw new InvalidArgumentException('Invalid created at value.');
        }

        if (!isset($attributes['updated_at']) || !($attributes['updated_at'] instanceof Carbon)) {
            throw new InvalidArgumentException('Invalid updated at value.');
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
     * @return string
     */
    public function getValue(): string
    {
        return $this->email;
    }

    /**
     * @inheritdoc
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}
