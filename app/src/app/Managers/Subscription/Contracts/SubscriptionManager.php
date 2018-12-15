<?php

namespace App\Managers\Subscription\Contracts;

use App\ValueObjects\SubscriptionEntity;

interface SubscriptionManager
{
    /**
     * Create a subscription by email.
     *
     * @param array $attributes
     * @return SubscriptionEntity
     */
    public function create(array $attributes): SubscriptionEntity;

    /**
     * Get the subscription by token.
     *
     * @param string $token
     * @return SubscriptionEntity
     */
    public function getByToken(string $token): SubscriptionEntity;

    /**
     * Paginate over subscriptions.
     *
     * @param int $page
     * @param int $perPage
     * @param array $filters
     * @return mixed
     */
    public function paginate(int $page, int $perPage, array $filters = []);

    /**
     * Delete the subscription by token.
     *
     * @param string $token
     * @return void
     */
    public function deleteByToken(string $token): void;
}
