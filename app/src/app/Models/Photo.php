<?php

namespace App\Models;

use App\Models\Builders\PhotoBuilder;
use App\Models\Tables\Constant;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Photo.
 *
 * @property int id
 * @property int created_by_user_id
 * @property int location_id
 * @property string path
 * @property string avg_color
 * @property array metadata
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property User createdByUser
 * @property User location
 * @property Collection thumbnails
 * @property Post post
 * @property Collection posts
 * @package App\Models
 */
class Photo extends Model
{
    /**
     * @inheritdoc
     */
    protected $attributes = [
        'path' => '',
        'avg_color' => '',
        'metadata' => '',
    ];

    /**
     * @inheritdoc
     */
    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * @inheritdoc
     */
    protected $fillable = [
        'created_by_user_id',
        'location_id',
        'path',
        'avg_color',
        'metadata',
    ];

    /**
     * @inheritdoc
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function (self $photo) {
            $photo->thumbnails()->detach();
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function thumbnails()
    {
        return $this->belongsToMany(Thumbnail::class, Constant::TABLE_PHOTOS_THUMBNAILS)->orderBy('width')->orderBy('height');
    }

    /**
     * @inheritdoc
     */
    public function newEloquentBuilder($query): PhotoBuilder
    {
        return new PhotoBuilder($query);
    }

    /**
     * @inheritdoc
     */
    public function newQuery(): PhotoBuilder
    {
        return parent::newQuery();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    /**
     * @return Post|null
     */
    public function getPostAttribute(): ?Post
    {
        $this->setRelation('post', collect($this->posts)->first());

        $photo = $this->getRelation('post');

        return $photo;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function posts()
    {
        return $this->belongsToMany(Post::class, Constant::TABLE_POSTS_PHOTOS);
    }

    /**
     * @return string|null
     */
    public function getManufacturerAttribute(): ?string
    {
        return $this->metadata['ifd0.Make'] ?? $this->metadata['exif.MakerNote'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getModelAttribute(): ?string
    {
        return $this->metadata['ifd0.Model'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getExposureTimeAttribute(): ?string
    {
        $raw = (string) $this->metadata['exif.ExposureTime'];

        [$numerator, $denominator] = explode('/', $raw);

        if (!is_numeric($numerator) || !is_numeric($denominator)) {
            return null;
        }

        $value = $denominator / $numerator;

        return '1/' . number_format($value, 0);
    }

    /**
     * @return string|null
     */
    public function getApertureAttribute(): ?string
    {
        $raw = (string) $this->metadata['exif.FNumber'];

        [$numerator, $denominator] = explode('/', $raw);

        if (!is_numeric($numerator) || !is_numeric($denominator)) {
            return null;
        }

        $value = $numerator / $denominator;

        return 'f/' . number_format($value, 1);
    }

    /**
     * @return string|null
     */
    public function getIsoAttribute(): ?string
    {
        return $this->metadata['exif.ISOSpeedRatings'] ?? null;
    }

    /**
     * @return Carbon|null
     */
    public function getTakenAtAttribute(): ?string
    {
        $takenAt = $this->metadata['exif.DateTimeOriginal'];

        if (!$takenAt) {
            return null;
        }

        return new Carbon($takenAt);
    }

    /**
     * @return string|null
     */
    public function getSoftwareAttribute(): ?string
    {
        return $this->metadata['ifd0.Software'] ?? null;
    }
}
