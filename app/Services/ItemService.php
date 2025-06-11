<?php

namespace App\Services;

use App\Models\Item;
use Illuminate\Support\Facades\Log;
use Exception;

class ItemService
{
    public function getAllItems($filteringData)
    {
        try {
            $items = Item::query()->select('id','name', 'price')
                ->when(!empty($filteringData), function ($query) use ($filteringData) {
                    foreach ($filteringData as $key => $value) {
                        $query->where($key, $value);
                    }
                });

            $items = $items->get();

            return [
                'status' => 200,
                'message' => __('item.get_successful'),
                'data' => $items
            ];
            Log::error('getAllItems: ' . json_encode($items->get()));
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
            $item = Item::create([
                'user_id'        => auth()->user()->id,
                'category_id'    => $data["category_id"],
                'subCategory_id' => $data["subCategory_id"],
                'name'           => $data["name"],
                'price'          => $data["price"],
                'type'        => $data["type"],
                'description'    => $data["description"] ?? null,
                'details'        => $data["details"] ?? null,
            ]);

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
     * @param Item $item
     * @param array $data
     * @return array
     */
    public function updateItem($id, $data)
    {
        try {
            $item = Item::findOrFail($id);

            $item->update([
                'category_id'    => $data['category_id'] ?? $item->category_id,
                'subCategory_id' => $data['subCategory_id'] ?? $item->subCategory_id,
                'name'           => $data['name'] ?? $item->name,
                'price'          => $data['price'] ?? $item->price,
                'type'           => $data["type"] ?? $item->type,
                'description'    => $data['description'] ?? $item->description,
                'details'        => $data['details'] ?? $item->details,
            ]);
            Log::error('updateItem $data content:', $data);
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
     * Soft delete the given item (moves it to trash).
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

    public function getItemData($id)
    {
        try {

            $item = Item::with('user')->findOrFail($id);

            // if ($item->user) {
            //     $item->user->average_rate = $item->user->averageRate();
            // }
            return [
                'status' => 200,
                'message' => __('item.get_successful'),
                'data' => $item
            ];
        } catch (Exception $e) {
            Log::error('Error in getItemData: ' . $e->getMessage());

            return [
                'status' => 500,
                'message' => __('general.failed'),
            ];
        }
    }
}
