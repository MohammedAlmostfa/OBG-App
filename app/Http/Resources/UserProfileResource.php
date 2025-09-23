<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'firstName'     => $this->first_name,
            'lastName'      => $this->last_name,
            'averageRating' => $this->averageRatings() ?? 0,
            'phone'         => $this->profile->phone ?? null,
            'countRatings'  => $this->countRatings() ?? 0,
            'photo_url'     => $this->photo->first()
                ? asset('storage/' . $this->photo->first()->url)
                : null,
        ];
    }
}
