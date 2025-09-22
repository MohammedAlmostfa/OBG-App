<?php

namespace App\Services;

use App\Models\Item;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

/**
 * Class ItemService
 *
 * Handles all business logic related to items, including
 * CRUD operations, fetching nearby items, and getting item details.
 *
 * @package App\Services
 */
class ItemService
{
    /**
     * Get all items with optional filtering applied.
     *
     * @param array $filteringData Key-value array of filters.
     * @return array Returns status, message, and paginated items.
     */
    public function getAllItems($filteringData)
    {
        try {
            $items = Item::query()
                ->select('items.*')
                ->with(['photos' => function ($query) {
                    $query->select('id', 'url', 'photoable_id')
                        ->orderBy('id')
                        ->limit(1);
                }])
                ->addSelect([
                    DB::raw('CASE WHEN EXISTS (
                        SELECT 1 FROM item_user
                        WHERE item_user.item_id = items.id
                          AND item_user.user_id = ' . (int)auth()->id() . '
                    ) THEN 1 ELSE 0 END AS is_saved')
                ])
                ->when(!empty($filteringData), function ($query) use ($filteringData) {
                    $query->filter($filteringData); // Apply custom filters
                })
                ->orderBy('id', 'desc')
                ->where('status', 1) // Only active items
                ->paginate(10);      // Paginate results

            return [
                'status' => 200,
                'message' => __('item.get_successful'),
                'data' => $items
            ];
        } catch (Exception $e) {
            Log::error('Error in getAllItems: ' . $e->getMessage());

            return [
                'status' => 500,
                'message' => [
                    'errorDetails' => __('general.failed'),
                ],
            ];
        }
    }

    /**
     * Get items near the authenticated user (within 5km radius).
     *
     * Uses Haversine formula to calculate distance based on latitude & longitude.
     *
     * @return array Returns status, message, and paginated nearby items.
     */
    public function getNearItems()
    {
        try {
            $user = auth()->user();

            if (!$user || !$user->profile) {
                return [
                    'status' => 404,
                    'message' => __('profile.not_found'),
                ];
            }

            $lat = $user->profile->latitude;
            $lng = $user->profile->longitude;

            $items = Item::query()
                ->select('items.*')
                ->selectRaw(
                    "(6371 * acos(cos(radians(?)) * cos(radians(profiles.latitude))
                      * cos(radians(profiles.longitude) - radians(?))
                      + sin(radians(?)) * sin(radians(profiles.latitude)))) AS distance",
                    [$lat, $lng, $lat]
                )
                ->join('users', 'items.user_id', '=', 'users.id')
                ->join('profiles', 'users.id', '=', 'profiles.user_id')
                ->with(['photos' => function ($query) {
                    $query->select('id', 'url', 'photoable_id')
                        ->orderBy('id')
                        ->limit(1);
                }])
                ->addSelect([
                    DB::raw('CASE WHEN EXISTS (
                        SELECT 1 FROM item_user
                        WHERE item_user.item_id = items.id
                          AND item_user.user_id = ' . (int)auth()->id() . '
                    ) THEN 1 ELSE 0 END AS is_saved')
                ])
                ->where('items.status', 1)
                ->having('distance', '<=', 5) // Limit to 5km
                ->orderBy('distance', 'asc')  // Closest first
                ->paginate(10);

            return [
                'status'  => 200,
                'message' => __('item.get_successful'),
                'data'    => $items,
            ];
        } catch (Exception $e) {
            Log::error('Error in getNearItems: ' . $e->getMessage());

            return [
                'status' => 500,
                'message' => [
                    'errorDetails' => __('general.failed'),
                ],
            ];
        }
    }

    /**
     * Store a new item in the database.
     *
     * @param array $data Input data validated from request.
     * @return array Returns status and message.
     */


    public function storeItem($data)
    {
        try {
            return DB::transaction(function () use ($data) {
                $user = auth()->user();


                $item = Item::create([
                    'user_id'        => $user->id,
                    'category_id'    => $data["category_id"],
                    'sub_category_id' => $data["subCategory_id"],
                    'name'           => $data["name"],
                    'price'          => $data["price"],
                    'type'           => $data["type"],
                    'status'            => $data['status'],
                    'description'    => $data["description"] ?? null,
                ]);


                if (!empty($data['photos']) && is_array($data['photos'])) {
                    foreach ($data['photos'] as $photo) {
                        $imageName = Str::random(32) . '.' . $photo->getClientOriginalExtension();
                        $folder = 'items/photos/' . now()->format('Y-m-d');
                        $path = $photo->storeAs($folder, $imageName, 'public');

                        $item->photos()->create(['url' => $path]);
                    }
                }

                return [
                    'status' => 200,
                    'message' => __('item.create_successful'),
                ];
            });
        } catch (Exception $e) {
            Log::error('Error in storeItem: ' . $e->getMessage());

            return [
                'status' => 500,
                'message' => [
                    'errorDetails' => __('general.failed'),
                ],
            ];
        }
    }

    /**
     * Update an existing item with new data and handle photo replacement.
     *
     * @param int $id Item ID to update.
     * @param array $data Input data validated from request.
     * @return array Returns status and message.
     */
    public function updateItem($id, $data)
    {
        try {
            return DB::transaction(function () use ($id, $data) {
                $item = Item::findOrFail($id);

                // Update basic fields
                $item->update([
                    'category_id'     => $data['category_id'] ?? $item->category_id,
                    'sub_category_id' => $data['subCategory_id'] ?? $item->sub_category_id,
                    'name'            => $data['name'] ?? $item->name,
                    'price'           => $data['price'] ?? $item->price,
                    'type'            => $data["type"] ?? $item->type,
                    'status'            => $data["status"] ?? $item->status,
                    'description'     => $data['description'] ?? $item->description,
                ]);

                // Replace photos if new ones provided
                if (!empty($data['photos']) && is_array($data['photos'])) {

                    // Delete old photos from storage and database
                    foreach ($item->photos as $oldPhoto) {
                        if (Storage::disk('public')->exists($oldPhoto->url)) {
                            Storage::disk('public')->delete($oldPhoto->url);
                        }
                        $oldPhoto->delete();
                    }

                    // Upload new photos
                    foreach ($data['photos'] as $photo) {
                        if ($photo instanceof \Illuminate\Http\UploadedFile) {
                            $imageName = Str::random(32) . '.' . $photo->getClientOriginalExtension();
                            $folder = 'items/photos/' . now()->format('Y-m-d');
                            $path = $photo->storeAs($folder, $imageName, 'public');

                            $item->photos()->create(['url' => $path]);
                        }
                    }
                }

                return [
                    'status' => 200,
                    'message' => __('item.update_successful'),
                ];
            });
        } catch (Exception $e) {
            Log::error('updateItem $data content:', $data);
            Log::error('Error in updateItem: ' . $e->getMessage());

            return [
                'status' => 500,
                'message' => [
                    'errorDetails' => __('general.failed'),
                ],
            ];
        }
    }

    /**
     * Soft delete an item (move to trash) and delete associated photos.
     *
     * @param Item $item
     * @return array Returns status and message.
     */
    public function softDeleteItem(Item $item)
    {
        try {
            // Delete associated photos from storage
            foreach ($item->photos as $photo) {
                if (Storage::disk('public')->exists($photo->url)) {
                    Storage::disk('public')->delete($photo->url);
                }
            }

            $item->delete(); // Soft delete

            return [
                'status' => 200,
                'message' => __('item.deleted'),
            ];
        } catch (Exception $e) {
            Log::error('Error in softDeleteItem: ' . $e->getMessage());
            return [
                'status' => 500,
                'message' => [
                    'errorDetails' => __('general.failed'),
                ],
            ];
        }
    }

    /**
     * Permanently delete a soft-deleted item and remove associated photos.
     *
     * @param int $id Item ID to force delete.
     * @return array Returns status and message.
     */
    public function forceDeleteItem($id)
    {
        try {
            $item = Item::withTrashed()->findOrFail($id);

            // Delete associated photos from storage
            foreach ($item->photos as $photo) {
                if (Storage::disk('public')->exists($photo->url)) {
                    Storage::disk('public')->delete($photo->url);
                }
            }

            $item->forceDelete(); // Permanently delete

            return [
                'status' => 200,
                'message' => __('item.force_deleted'),
            ];
        } catch (Exception $e) {
            Log::error('Error in forceDeleteItem: ' . $e->getMessage());
            return [
                'status' => 500,
                'message' => [
                    'errorDetails' => __('general.failed'),
                ],
            ];
        }
    }

    /**
     * Get a single item along with user, photos, and similar items.
     *
     * @param int $id Item ID to fetch.
     * @return array Returns status, message, and item + similar items data.
     */
    public function getItemData($id)
    {
        try {
            // Get main item with all related data
            $item = Item::with([
                'user:id,name',
                'user.photo',
                'category:id,name',
                'subCategory:id,name',
                'photos',
                 'user.ratingsReceived' => fn($q) => $q->latest()->limit(5)
                        ->select('id', 'rate', 'review', 'user_id', 'rated_user_id')
                        ->with([
                            'reviewer:id,name',
                            'reviewer.photo:id,photoable_id,photoable_type,url'
                        ]),

            ])
            ->findOrFail($id);

            // Get similar items from the same sub-category (excluding current)
            $similarItems = Item::query()
                ->select('items.*')
                ->with(['photos' => function ($query) {
                    $query->select('id', 'url', 'photoable_id')
                        ->orderBy('id')
                        ->limit(1);
                }])
                ->addSelect([
                    DB::raw('CASE WHEN EXISTS (
                        SELECT 1 FROM item_user
                        WHERE item_user.item_id = items.id
                          AND item_user.user_id = ' . (int)auth()->id() . '
                    ) THEN 1 ELSE 0 END AS is_saved')
                ])
                ->where('sub_category_id', $item->sub_category_id)
                ->where('status', 1)
                ->where('id', '!=', $item->id) // exclude current item
                    ->limit(5)->get();

            return [
                'status' => 200,
                'message' => __('item.get_successful'),
                'data' => [
                    'item' => $item,
                    'similar_items' => $similarItems
                ]
            ];
        } catch (Exception $e) {
            Log::error('Error in getItemData: ' . $e->getMessage());

            return [
                'status' => 500,
                'message' => [
                    'errorDetails' => [__('auth.login_failed')],
                ],
            ];
        }
    }
}
