<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $firstPhoto = $this->photos->first();

        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'price'     => (float) $this->price,
            'photo_url' => $firstPhoto
                ? $request->getSchemeAndHttpHost() . '/storage/' . ltrim($firstPhoto->url, '/')
                : null,
            'is_saved'  => (bool) ($this->is_saved ?? false),
        ];
    }
}
