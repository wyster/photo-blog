<?php

namespace Api\V1\Http\Resources;

use App\Models\Post;
use Illuminate\Http\Resources\Json\Resource;
use function App\Util\html_purify;
use function App\Util\to_bool;
use function App\Util\to_int;
use function App\Util\to_string;

/**
 * Class PostPlainResource.
 *
 * @package Api\V1\Http\Resources
 */
class PostPlainResource extends Resource
{
    /**
     * @var Post
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
            'is_published' => to_bool(html_purify($this->resource->is_published)),
            'description' => to_string(html_purify($this->resource->description)),
            'published_at' => to_string(html_purify(optional($this->resource->published_at)->toAtomString())),
            'created_at' => to_string(html_purify(optional($this->resource->created_at)->toAtomString())),
            'updated_at' => to_string(html_purify(optional($this->resource->updated_at)->toAtomString())),
        ];
    }
}
