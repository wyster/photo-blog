<?php

namespace Api\V1\Http\Resources;

use App\Models\Location;
use Illuminate\Http\Resources\Json\Resource;
use function App\Util\html_purify;
use function App\Util\to_float;

/**
 * Class LocationPlainResource.
 *
 * @package Api\V1\Http\Resources
 */
class LocationPlainResource extends Resource
{
    /**
     * @var Location
     */
    public $resource;

    /**
     * @inheritdoc
     */
    public function toArray($request)
    {
        return [
            'latitude' => to_float(html_purify($this->resource->coordinates->getLatitude())),
            'longitude' => to_float(html_purify($this->resource->coordinates->getLongitude())),
        ];
    }
}
