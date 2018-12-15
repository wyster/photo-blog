<?php

namespace App\Managers\Location;

use App\Models\Location;
use App\ValueObjects\CoordinatesEntity;
use App\ValueObjects\LatitudeEntity;
use App\ValueObjects\LongitudeEntity;
use Illuminate\Database\ConnectionInterface as Database;

/**
 * Class LocationManager.
 *
 * @package App\Managers\Location
 */
class LocationManager
{
    /**
     * @var Database
     */
    private $database;

    /**
     * @var LocationValidator
     */
    private $validator;

    /**
     * LocationManager constructor.
     *
     * @param Database $database
     * @param LocationValidator $validator
     */
    public function __construct(Database $database, LocationValidator $validator)
    {
        $this->database = $database;
        $this->validator = $validator;
    }

    /**
     * Create a location.
     *
     * @param array $attributes
     * @return Location
     */
    public function create(array $attributes): Location
    {
        $attributes = $this->validator->validateForCreate($attributes);

        $coordinates = new CoordinatesEntity(new LatitudeEntity($attributes['latitude']), new LongitudeEntity($attributes['longitude']));

        $location = (new Location)->fill(['coordinates' => $coordinates]);

        $location->save();

        return $location;
    }
}
