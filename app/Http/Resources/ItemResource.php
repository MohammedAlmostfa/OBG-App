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
        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'type'      => $this->type,
            'price'     => (float) $this->price,
            'is_saved'  => (bool) ($this->is_saved ?? false),
            'photo_url' => optional($this->photos->first())->url,
        ];
    }
}
