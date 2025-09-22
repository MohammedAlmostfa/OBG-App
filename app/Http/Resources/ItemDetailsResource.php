<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemDetailsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $item = $this['item'];

        return [
            'item'          => new ItemDataResource($item),
            'user'     => new UserProfileResource($item->user),
            'ratings'  => UserRatingResource::collection($item->user->ratingsReceived ?? []),
            'similar_items' => ItemResource::collection($this['similar_items']),


        ];
    }
}
