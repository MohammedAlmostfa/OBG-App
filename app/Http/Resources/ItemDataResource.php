<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemDataResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'description'   => $this->description,
            'price'         => (float) $this->price,
            'type'          => $this->type,
            'status'        => $this->status,
            'availability'  => $this->availability,

            // Category & Subcategory
            'category' => [
                'id'   => $this->category->id ?? null,
                'name' => $this->category->name ?? null,
            ],
            'sub_category' => [
                'id'   => $this->subCategory->id ?? null,
                'name' => $this->subCategory->name ?? null,
            ],

            // Photos of the item
            'photos' => $this->photos->map(fn($photo) => [
                'id'  => $photo->id,
                'url' => asset('storage/' . $photo->url),
            ]),

            // User info
            'user' => [
                'id'             => $this->user->id ?? null,

                'name' => $this->user->name,
                'firstName' => $this->user->first_name,
                'lastName' => $this->user->last_name,
                'averageRating' => $this->user->averageRatings() ?? 0,
                'countRatings' => $this->user->countRatings(),
                'photo_url'          => $this->user->photo->first()
                    ? asset('storage/' . $this->user->photo->first()->url)
                    : null,


                'ratings' => $this->user->ratingsReceived->map(function ($rating) {
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
            ],
        ];
    }
}
