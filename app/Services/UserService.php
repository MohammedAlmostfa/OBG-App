<?php

namespace App\Services;

use App\Models\Item;
use App\Models\Rating;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * Class UserService
 * 
 * Handles business logic related to users, including retrieving ratings and items.
 */
class UserService
{
    /**
     * Retrieve ratings for a specific user.
     * 
     * @param int $id The user ID for whom ratings are being retrieved.
     * @return array Response containing status and retrieved data.
     */
    public function getUserRating($id)
    {
        try {
            // Fetch ratings with reviewer details and associated photo
            $ratings = Rating::with([
                'reviewer:name,id',    // Get reviewer name and ID
                'reviewer.photo:url,photoable_id' // Fetch reviewer's photo details
            ])->where('user_id', $id)->get();

            return [
                'status'  => 200,
                'message' => __('rate.getting_successful'),
                'data'    => $ratings,
            ];
        } catch (Exception $e) {
            // Log error and return failure response
            Log::error('Unexpected error in getUserRating: ' . $e->getMessage());

            return [
                'status'  => 500,
                'message' => __('general.failed'),
            ];
        }
    }

    /**
     * Retrieve items belonging to a specific user.
     *
     * @param int $id The user ID whose items need to be fetched.
     * @return array Response containing status and retrieved items.
     */
    public function getUserItems($id)
    {
        try {
            // Retrieve items belonging to the user
            $items = Item::select(['user_id', 'price', 'name'])
                ->where('user_id', $id)
                ->get();

            return [
                'status'  => 200,
                'message' => __('item.get_successful'),
                'data'    => $items,
            ];
        } catch (Exception $e) {
            // Log error and return failure response
            Log::error('Error in getUserItems: ' . $e->getMessage());

            return [
                'status'  => 500,
                'message' => __('general.failed'),
            ];
        }
    }
    public function getUserData($id)
{
    try {
        $user = User::select(['id', 'name' ])
            ->withCount(['ratings as countRatings', 'items as countItems'])
            ->findOrFail($id);

        return [
            'status'  => 200,
            'message' => __('user.get_successful'),
            'data'    => $user,
        ];
    } catch (Exception $e) {
        Log::error('Error in getUserData: ' . $e->getMessage());

        return [
            'status'  => 500,
            'message' => __('general.failed'),
        ];
    }
}

}
