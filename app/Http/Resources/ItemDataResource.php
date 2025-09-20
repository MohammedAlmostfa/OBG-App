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
                'name'           => $this->user->name ?? null,
                'average_rating' => $this->user->ratings()->avg('rate') ?? 0,
                'photo_url'          => $this->user->photo->first()
                                    ? asset('storage/' . $this->user->photo->first()->url)
                                    : null,
                'ratings'        => $this->user->ratings->map(fn($rating) => [
                    'user_id'  => $rating->user_id,
                    'review'   => $rating->review,
                    'rate'     => $rating->rate,
                    'reviewer' => [
                        'id'             => $rating->reviewer->id ?? null,
                        'name'           => $rating->reviewer->name ?? null,
                        'average_rating' => $rating->reviewer->ratings()->avg('rate') ?? 0,
                        'photo_url'          => $rating->reviewer->photo->first()
                                            ? asset('storage/' . $rating->reviewer->photo->first()->url)
                                            : null,
                    ],
                ]),
            ],
        ];
    }
}
