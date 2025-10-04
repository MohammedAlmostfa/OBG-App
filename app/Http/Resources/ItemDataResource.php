<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemDataResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'description' => $this->description,
            'price'       => (float) $this->price,
            'type'        => $this->type,
            'status'      => $this->status,
            'availability'=> $this->availability,

            'category' => [
                'id'   => $this->category->id ?? null,
                'name' => $this->category->name ?? null,
            ],
            'sub_category' => [
                'id'   => $this->subCategory->id ?? null,
                'name' => $this->subCategory->name ?? null,
            ],

    'photos' => $this->photos->map(fn($photo) => [
                'id'  => $photo->id,
                'url' =>      $this->photos->first()->url
            ]),

        ];
    }
}
