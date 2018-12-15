<?php

namespace App\Dom\Entities;

use InvalidArgumentException;

/**
 * Class SubscriptionEntity.
 *
 * @package App\Dom\Entities
 */
final class SubscriptionEntity extends AbstractEntity
{
    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $token;

    /**
     * SubscriptionEntity constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        parent::__construct($attributes);

        $this->email = $attributes['email'];
        $this->token = $attributes['token'];
    }

    /**
     * @inheritdoc
     */
    public function __toString(): string
    {
        return $this->getToken();
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
            'email' => $this->getEmail(),
            'token' => $this->getToken(),
        ];
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @inheritdoc
     */
    protected function assertAttributes(array $attributes): void
    {
        if (!isset($attributes['email']) || !is_string($attributes['email'])) {
            throw new InvalidArgumentException('Invalid email value.');
        }

        if (!isset($attributes['token']) || !is_string($attributes['token'])) {
            throw new InvalidArgumentException('Invalid token value.');
        }
    }
}
