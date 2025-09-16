<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemDetailsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'item' => new ItemDataResource($this['item']),
            'similar_items' => ItemResource::collection($this['similar_items']),
        ];
    }
}
