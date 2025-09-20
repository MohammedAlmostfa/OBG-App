<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserRatingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'rate'       => $this->rate,
            'review'     => $this->review,

            'reviewer'   => [
                'id'             => $this->reviewer->id ?? null,
                'name'           => $this->reviewer->name ?? null,
                'photo_url'      => $this->reviewer->photo->first()
                    ? asset('storage/' . $this->reviewer->photo->first()->url)
                    : null,
            ],
        ];
    }
}
