<?php

namespace App\Models;

use App\Dom\Entities\TagEntity;
use App\Models\Builders\TagBuilder;
use App\Models\Tables\Constant;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Tag.
 *
 * @property int id
 * @property string value
 * @property Collection posts
 * @package App\Models
 */
class Tag extends Model
{
    /**
     * @inheritdoc
     */
    public $timestamps = false;
    /**
     * @inheritdoc
     */
    protected $fillable = [
        'value',
    ];

    /**
     * @inheritdoc
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function (self $tag) {
            $tag->posts()->detach();
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function posts()
    {
        return $this->belongsToMany(Post::class, Constant::TABLE_POSTS_TAGS);
    }

    /**
     * @inheritdoc
     */
    public function newEloquentBuilder($query): TagBuilder
    {
        return new TagBuilder($query);
    }

    /**
     * @inheritdoc
     */
    public function newQuery(): TagBuilder
    {
        return parent::newQuery();
    }

    /**
     * Setter for the 'value' attribute.
     *
     * @param string $value
     * @return $this
     */
    public function setValueAttribute(string $value)
    {
        $this->attributes['value'] = trim(str_replace(' ', '_', strtolower($value)));

        return $this;
    }

    /**
     * @return TagEntity
     */
    public function toEntity(): TagEntity
    {
        return TagEntity::fromArray($this->toArray());
    }
}
