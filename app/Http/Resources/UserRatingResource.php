<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserRatingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'rate'       => $this->rate,
            'review'     => $this->review,
            'created_at' => $this->created_at,
            'user_name'  => $this->reviewer->name,
            'user_id'    => $this->reviewer->id,
            'photo_url' => optional($this->reviewer->photo->first())->url,

        ];
    }
}
