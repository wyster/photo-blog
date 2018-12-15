<?php

namespace App\Models;

use App\Dom\Entities\SubscriptionEntity;
use App\Models\Builders\SubscriptionBuilder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Subscription.
 *
 * @property int id
 * @property string email
 * @property string token
 * @package App\Models
 */
class Subscription extends Model
{
    /**
     * @inheritdoc
     */
    public $timestamps = false;
    /**
     * @inheritdoc
     */
    protected $fillable = [
        'email',
    ];

    /**
     * @inheritdoc
     */
    public function newEloquentBuilder($query): SubscriptionBuilder
    {
        return new SubscriptionBuilder($query);
    }

    /**
     * @inheritdoc
     */
    public function newQuery(): SubscriptionBuilder
    {
        return parent::newQuery();
    }

    /**
     * @return SubscriptionEntity
     */
    public function toEntity(): SubscriptionEntity
    {
        return SubscriptionEntity::fromArray($this->toArray());
    }
}
