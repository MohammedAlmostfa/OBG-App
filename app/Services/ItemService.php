<?php

namespace App\Services;

use App\Models\Item;
use Exception;
use App\Models\User;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ItemService
{
    /**
     * Get all items with optional filtering.
     *
     * @param array $filteringData
     * @return array
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
                    $query->filter($filteringData);
                })->orderBy('id', 'desc')->where('status', 1)->paginate(10);


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
     * Store a newly created item in the database.
     *
     * @param array $data
     * @return array
     */
    public function storeItem($data)
    {
        try {
            $user = auth()->user();

            $item = Item::create([
                'user_id'        => $user->id,
                'category_id'    => $data["category_id"],
                'subCategory_id' => $data["subCategory_id"],
                'name'           => $data["name"],
                'price'          => $data["price"],
                'type'           => $data["type"],
                'description'    => $data["description"] ?? null,

            ]);

            if (!empty($data['photos']) && is_array($data['photos'])) {
                foreach ($data['photos'] as $photo) {
                    $imageName = Str::random(32) . '.' . $photo->getClientOriginalExtension();
                    $path = $photo->storeAs('items/photos', $imageName, 'public');
                    $item->photos()->create(['url' => $path]);
                }
            }

            return [
                'status' => 200,
                'message' => __('item.create_successful'),
            ];
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
     * Update the given item with provided data.
     *
     * @param int $id
     * @param array $data
     * @return array
     */
    public function updateItem($id, $data)
    {
        try {
            $item = Item::findOrFail($id);

            $item->update([
                'category_id'    => $data['category_id'] ?? $item->category_id,
                'sub_category_id' => $data['subCategory_id'] ?? $item->subCategory_id,
                'name'           => $data['name'] ?? $item->name,
                'price'          => $data['price'] ?? $item->price,
                'type'           => $data["type"] ?? $item->type,
                'description'    => $data['description'] ?? $item->description,

            ]);

            if (!empty($data['photos']) && is_array($data['photos'])) {
                $item->photos()->delete();

                foreach ($data['photos'] as $photo) {
                    if ($photo instanceof \Illuminate\Http\UploadedFile) {
                        $imageName = Str::random(32) . '.' . $photo->getClientOriginalExtension();
                        $path = $photo->storeAs('items/photos', $imageName, 'public');
                        $item->photos()->create(['url' => $path]);
                    }
                }
            }

            return [
                'status' => 200,
                'message' => __('item.update_successful'),
            ];
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
     * Soft delete the given item.
     *
     * @param Item $item
     * @return array
     */
    public function softDeleteItem(Item $item)
    {
        try {
            $item->delete();

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
     * Permanently delete a soft-deleted item.
     *
     * @param int $id
     * @return array
     */
    public function forceDeleteItem($id)
    {
        try {
            $item = Item::withTrashed()->findOrFail($id);
            $item->forceDelete();

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
     * Get a single item with related user and media data.
     *
     * @param int $id
     * @return array
     */
    public function getItemData($id)
    {
        try {
            // Get the main item with relations
            $item = Item::with([
                'user:id,name',
                'user.photo',
                'category:id,name',
                'subCategory:id,name',
                'photos',
                'user.ratings:user_id,review,rate',
                'user.ratings.reviewer:id,name',
                'user.ratings.reviewer.photo',
            ])->findOrFail($id);

            // Get related items from the same sub_category (except the current one)
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
                ->get();

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
