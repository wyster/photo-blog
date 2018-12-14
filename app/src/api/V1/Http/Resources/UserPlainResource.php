<?php

namespace Api\V1\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\Resource;
use function App\Util\html_purify;
use function App\Util\to_int;
use function App\Util\to_string;

/**
 * Class UserPlainResource.
 *
 * @package Api\V1\Http\Resources
 */
class UserPlainResource extends Resource
{
    /**
     * @var User
     */
    public $resource;

    /**
     * @inheritdoc
     */
    public function toArray($request)
    {
        $visibleUserContacts = optional($request->user())->can('view-user-contacts', $this->resource);

        return [
            'id' => to_int(html_purify($this->resource->id)),
            'name' => to_string(html_purify($this->resource->name)),
            'email' => $this->when($visibleUserContacts, function () {
                return to_string(html_purify($this->resource->email));
            }),
            'role' => to_string(html_purify($this->resource->role->name)),
            'created_at' => to_string(html_purify(optional($this->resource->created_at)->toAtomString())),
            'updated_at' => to_string(html_purify(optional($this->resource->updated_at)->toAtomString())),
        ];
    }
}
