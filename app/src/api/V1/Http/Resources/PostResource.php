<?php

namespace Api\V1\Http\Resources;

use App\Models\Post;
use App\Models\Tag;
use function App\Util\to_object;

/**
 * Class PostResource.
 *
 * @package Api\V1\Http\Resources
 */
class PostResource extends PostPlainResource
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
        return array_merge(parent::toArray($request), [
            'photo' => to_object($this->resource->photo, PhotoResource::class),
            'tags' => collect($this->resource->tags)->map(function (Tag $tag) {
                return to_object($tag, TagPlainResource::class);
            }),
        ]);
    }
}
