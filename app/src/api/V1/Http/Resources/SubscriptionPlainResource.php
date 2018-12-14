<?php

namespace Api\V1\Http\Resources;

use App\Models\Subscription;
use Illuminate\Http\Resources\Json\Resource;
use function App\Util\html_purify;
use function App\Util\to_string;

/**
 * Class SubscriptionPlainResource.
 *
 * @package Api\V1\Http\Resources
 */
class SubscriptionPlainResource extends Resource
{
    /**
     * @var Subscription
     */
    public $resource;

    /**
     * @inheritdoc
     */
    public function toArray($request)
    {
        return [
            'email' => to_string(html_purify($this->resource->email)),
            'token' => to_string(html_purify($this->resource->token)),
        ];
    }
}
