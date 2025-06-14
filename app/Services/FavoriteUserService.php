<?php

namespace App\Services;

use Exception;
use App\Models\User; // Correct model reference
use Illuminate\Support\Facades\Log;

class FavoriteUserService
{
    /**
     * Add a user to the authenticated user's favorites list.
     *
     * @param int $userId The ID of the user to add.
     * @return array An array containing status code and message.
     */
    public function addToFavourite($userId)
    {
        try {
            // Retrieve the authenticated user
            $user = auth()->user();

            // Check if the target user exists
            if (!User::find($userId)) {
                return [
                    'status' => 404,
                    'message' => [
                        'errorDetails' => __('general.not_found'),
                    ],
                ];
            }

            // Add the user to favorites without removing existing relations
            $user->favoriteUsers()->syncWithoutDetaching([$userId]);

            return [
                'status'  => 200,
                'message' => __("user.saved_successful"),
            ];
        } catch (Exception $e) {
            // Log the error for debugging
            Log::error('Error in addToFavourite: ' . $e->getMessage());

            return [
                'status' => 500,
                'message' => [
                    'errorDetails' => __('general.failed'),
                ],
            ];
        }
    }

    /**
     * Remove a user from the authenticated user's favorites list.
     *
     * @param int $userId The ID of the user to remove.
     * @return array An array containing status code and message.
     */
    public function removeFromFavourite($userId)
    {
        try {
            // Retrieve the authenticated user
            $user = auth()->user();

            // Remove the user from favorites
            $user->favoriteUsers()->detach($userId);

            return [
                'status'  => 200,
                'message' => __("user.unsaved_successful"),
            ];
        } catch (Exception $e) {
            // Log the error for debugging
            Log::error('Error in removeFromFavourite: ' . $e->getMessage());

            return [
                'status' => 500,
                'message' => [
                    'errorDetails' => __('general.failed'),
                ],
            ];
        }
    }
}
