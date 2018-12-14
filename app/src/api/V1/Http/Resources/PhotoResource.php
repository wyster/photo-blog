<?php

namespace Api\V1\Http\Resources;

use function App\Util\html_purify;
use function App\Util\to_object;
use function App\Util\to_string;

/**
 * Class PhotoResource.
 *
 * @package Api\V1\Http\Resources
 */
class PhotoResource extends PhotoPlainResource
{
    /**
     * @inheritdoc
     */
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'location' => to_object($this->resource->location, LocationPlainResource::class),
            'exif' => [
                'manufacturer' => to_string(html_purify($this->resource->manufacturer)),
                'model' => to_string(html_purify($this->resource->model)),
                'exposure_time' => to_string(html_purify($this->resource->exposure_time)),
                'aperture' => to_string(html_purify($this->resource->aperture)),
                'iso' => to_string(html_purify($this->resource->iso)),
                'taken_at' => to_string(html_purify($this->resource->taken_at)),
                'software' => to_string(html_purify($this->resource->software)),
            ],
            'thumbnails' => [
                'medium' => to_object($this->resource->thumbnails->offsetGet(0), ThumbnailPlainResource::class),
                'large' => to_object($this->resource->thumbnails->offsetGet(1), ThumbnailPlainResource::class),
            ],
        ]);
    }
}
