<?php

namespace Api\V1\Http\Resources;

use App\Models\Photo;
use Illuminate\Http\Resources\Json\Resource;
use function App\Util\html_purify;
use function App\Util\to_int;
use function App\Util\to_string;

/**
 * Class PhotoPlainResource.
 *
 * @package Api\V1\Http\Resources
 */
class PhotoPlainResource extends Resource
{
    /**
     * @var Photo
     */
    public $resource;

    /**
     * @inheritdoc
     */
    public function toArray($request)
    {
        return [
            'id' => to_int(html_purify($this->resource->id)),
            'created_by_user_id' => to_int(html_purify($this->resource->created_by_user_id)),
            'avg_color' => to_string(html_purify($this->resource->avg_color)),
            'created_at' => to_string(html_purify(optional($this->resource->created_at)->toAtomString())),
        ];
    }
}
