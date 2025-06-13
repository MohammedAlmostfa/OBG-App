<?php

namespace App\Services;

use App\Models\Item;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * Class SavedItemService
 *
 * Service class responsible for handling operations related to saving and unsaving items.
 */
class SavedItemService
{
    /**
     * Save an item for the authenticated user.
     *
     * @param int $itemId The ID of the item to save.
     * @return array An array containing status code and message.
     */
    public function saveItem($itemId)
    {
        try {
            // Retrieve the authenticated user
            $user = auth()->user();

            // Check if the item exists
            if (!Item::find($itemId)) {
                return [
                    'status' => 404,
                    'message' => [
                        'errorDetails' => __('general.not_found'),
                    ],
                ];
            }

            // Save the item for the user without detaching any previous saved items
            $user->savedItems()->syncWithoutDetaching([$itemId]);

            return [
                'status'  => 200,
                'message' => __("item.Saved_Sucssful"),
            ];
        } catch (Exception $e) {
            // Log the error for debugging purposes
            Log::error('Error in saveItem: ' . $e->getMessage());

            return [
                'status' => 500,
                'message' => [
                    'errorDetails' => __('general.failed'),
                ],
            ];
        }
    }

    /**
     * Unsave an item for the authenticated user.
     *
     * @param int $itemId The ID of the item to unsave.
     * @return array An array containing status code and message.
     */
    public function unsaveItem($itemId)
    {
        try {
            // Retrieve the authenticated user
            $user = auth()->user();

            // Remove the saved item from the user's list
            $user->savedItems()->detach($itemId);

            return [
                'status'  => 200,
                'message' => __("item.un_saved_Sucssful"),
            ];
        } catch (Exception $e) {
            // Log the error for debugging purposes
            Log::error('Error in unsaveItem: ' . $e->getMessage());

            return [
                'status' => 500,
                'message' => [
                    'errorDetails' => __('general.failed'),
                ],
            ];
        }
    }
}
