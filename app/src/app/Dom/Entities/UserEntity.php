<?php

namespace App\Dom\Entities;

use Carbon\Carbon;
use InvalidArgumentException;

/**
 * Class UserEntity.
 *
 * @package App\Dom\Entities
 */
final class UserEntity extends AbstractEntity
{
    public const ROLE_ADMINISTRATOR = 'Administrator';
    public const ROLE_CUSTOMER = 'Customer';

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
        parent::__construct($attributes);

        $this->id = $attributes['id'];
        $this->name = $attributes['name'];
        $this->email = $attributes['email'];
        $this->role = $attributes['role'];
        $this->createdAt = new Carbon($attributes['created_at']);
        $this->updatedAt = new Carbon($attributes['updated_at']);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Carbon
     */
    public function getCreatedAt(): Carbon
    {
        return $this->createdAt->copy();
    }

    /**
     * @return Carbon
     */
    public function getUpdatedAt(): Carbon
    {
        return $this->updatedAt->copy();
    }

    /**
     * @return bool
     */
    public function isAdministrator(): bool
    {
        return $this->getRole() === static::ROLE_ADMINISTRATOR;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @return bool
     */
    public function isCustomer(): bool
    {
        return $this->getRole() === static::ROLE_CUSTOMER;
    }

    /**
     * @inheritdoc
     */
    public function __toString(): string
    {
        return $this->getEmail();
    }

    /**
     * @return string
     */
    public function getEmail(): string
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

    /**
     * @param $attributes
     * @return void
     * @throws InvalidArgumentException
     */
    protected function assertAttributes(array $attributes): void
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

        if (!isset($attributes['role']) || !is_string($attributes['created_at'])) {
            throw new InvalidArgumentException('Invalid created at value.');
        }

        if (!isset($attributes['role']) || !is_string($attributes['updated_at'])) {
            throw new InvalidArgumentException('Invalid updated at value.');
        }
    }
}
