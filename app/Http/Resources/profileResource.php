<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    public function toArray($request): array
    {
        return [

            'id'            => $this->id,
            'name'          => $this->name,
            'firstName'     => $this->first_name,
            'lastName'      => $this->last_name,
            'averageRating' => $this->ratings_received_avg_rate ?? 0,
            'countRatings'  => $this->ratings_received_count ?? 0,
            'photo_url'     => $this->photo?->first()->url
                ? asset('storage/' . $this->photo->first()->url)
                : null,
            'birthday'  => $this->profile->birthday ?? null,
            'phone'     => $this->profile->phone ?? null,
            'address'   => $this->profile->address ?? null,
            'latitude'  => $this->profile->latitude ?? null,
            'longitude' => $this->profile->longitude ?? null,
            'ratings' => UserRatingResource::collection($this->whenLoaded('ratingsReceived')),
            'items'   => ItemResource::collection($this->whenLoaded('items')),
        ];
    }
}
