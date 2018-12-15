<?php

namespace App\ValueObjects;

use Illuminate\Contracts\Support\Arrayable;
use InvalidArgumentException;

/**
 * Class SubscriptionEntity.
 *
 * @package App\ValueObjects
 */
final class SubscriptionEntity implements Arrayable
{
    /**
     * @var int
     */
    private $id;

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
        $this->assertAttributes($attributes);

        $this->id = $attributes['id'];
        $this->email = $attributes['email'];
        $this->token = $attributes['token'];
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

        if (!isset($attributes['email']) || !is_string($attributes['email'])) {
            throw new InvalidArgumentException('Invalid email value.');
        }

        if (!isset($attributes['token']) || !is_string($attributes['token'])) {
            throw new InvalidArgumentException('Invalid token value.');
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
            'email' => $this->email,
            'token' => $this->token,
        ];
    }
}
