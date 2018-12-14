<?php

namespace Api\V1\Http\Resources;

use App\Models\Thumbnail;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Storage;
use function App\Util\html_purify;
use function App\Util\to_int;
use function App\Util\to_string;
use function App\Util\url_storage;

/**
 * Class ThumbnailPlainResource.
 *
 * @package Api\V1\Http\Resources
 */
class ThumbnailPlainResource extends Resource
{
    /**
     * @var Thumbnail
     */
    public $resource;

    /**
     * @inheritdoc
     */
    public function toArray($request)
    {
        return [
            'url' => to_string(html_purify(function () {
                return url_storage(Storage::url($this->resource->path));
            })),
            'width' => to_int(html_purify($this->resource->width)),
            'height' => to_int(html_purify($this->resource->height)),
        ];
    }
}
