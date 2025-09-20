<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class profileResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'firstName' => $this->first_name,
            'lastName' => $this->last_name,
            'averageRating' => $this->ratings_received_avg_rate ?? 0,
            'countRatings' => $this->countRatings(),
            'photo_url' => $this->photo->first() ? asset('storage/' . $this->photo->first()->url) : null,


            'birthday' => $this->profile->birthday ?? null,
            'phone' => $this->profile->phone ?? null,
            'address' => $this->profile->address ?? null,
            'latitude' => $this->profile->latitude ?? null,
            'longitude' => $this->profile->longitude ?? null,


            'ratings' => $this->ratingsReceived->map(function($rating) {
                return [
                    'id' => $rating->id,
                    'rate' => $rating->rate,
                    'review' => $rating->review,
                    'reviewer' => [
                        'id' => $rating->reviewer->id,
                        'name' => $rating->reviewer->name,
                        'photo_url' => $rating->reviewer->photo->first()
                            ? asset('storage/' . $rating->reviewer->photo->first()->url)
                            : null,
                    ],
                ];
            }),


            'items' => $this->items->map(function($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,

                    'price' => $item->price,
                ];
            }),
        ];
    }
}
