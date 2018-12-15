<?php

namespace App\Models;

use App\Models\Builders\LocationBuilder;
use App\Models\Tables\Constant;
use App\Dom\ValueObjects\CoordinatesEntity;
use App\Dom\ValueObjects\LatitudeEntity;
use App\Dom\ValueObjects\LongitudeEntity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Class Location.
 *
 * Note: Laravel does not support spatial types.
 * See: https://dev.mysql.com/doc/refman/5.7/en/spatial-type-overview.html
 *
 * @property int id
 * @property CoordinatesEntity coordinates
 * @package App\Models
 */
class Location extends Model
{
    /**
     * @inheritdoc
     */
    public $timestamps = false;
    /**
     * @inheritdoc
     */
    protected $table = Constant::TABLE_LOCATIONS;
    /**
     * @inheritdoc
     */
    protected $fillable = [
        'coordinates',
    ];

    /**
     * @param CoordinatesEntity $coordinates
     * @return $this
     */
    public function setCoordinatesAttribute(CoordinatesEntity $coordinates)
    {
        $expression = "ST_GeomFromText('POINT({$coordinates})')";

        $this->attributes['coordinates'] = $this->getConnection()->raw($expression);

        return $this;
    }

    /**
     * @return CoordinatesEntity
     */
    public function getCoordinatesAttribute(): CoordinatesEntity
    {
        $text = Str::before(Str::after($this->attributes['coordinates'], 'POINT('), ')');

        [$latitude, $longitude] = explode(' ', $text);

        return new CoordinatesEntity(new LatitudeEntity($latitude), new LongitudeEntity($longitude));
    }

    /**
     * @inheritdoc
     */
    public function newEloquentBuilder($query): LocationBuilder
    {
        return (new LocationBuilder($query))->defaultSelect();
    }

    /**
     * @inheritdoc
     */
    public function newQuery(): LocationBuilder
    {
        return parent::newQuery();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function photos()
    {
        return $this->hasMany(Photo::class, 'location_id');
    }
}
