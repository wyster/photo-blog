<?php

namespace App\Managers\Location;

use App\Dom\ValueObjects\Coordinates;
use App\Dom\ValueObjects\Latitude;
use App\Dom\ValueObjects\Longitude;
use App\Models\Location;
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

        $coordinates = new Coordinates(new Latitude($attributes['latitude']), new Longitude($attributes['longitude']));

        $location = (new Location)->fill(['coordinates' => $coordinates]);

        $location->save();

        return $location;
    }
}
