<?php

namespace App\Services;

use App\Models\Item;
use App\Models\Rating;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
/**
 * Class UserService
 *
 * Handles business logic related to users, including retrieving ratings, items, saved items, and favorite users.
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
            // Fetch ratings along with reviewer details and associated photo
            $ratings = Rating::with([
                'reviewer:name,id',    // Retrieve reviewer's name and ID
                'reviewer.photo:url,photoable_id' // Fetch reviewer's profile photo
            ])->where('user_id', $id)->get();

            return [
                'status'  => 200,
                'message' => __('rate.getting_successful'),
                'data'    => $ratings,
            ];
        } catch (Exception $e) {
            // Log the error for debugging
            Log::error('Unexpected error in getUserRating: ' . $e->getMessage());

            return [
                'status'  => 500,
                'message' => [
                    'errorDetails' => __('general.failed'),
                ],
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
            // Fetch items belonging to the user
            $items = Item::select(['id', 'user_id', 'price', 'name'])
                ->with(['photos' => function ($query) {
                    $query->select('id', 'url', 'photoable_id')->orderBy('id')->limit(1);
                }])
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
                'message' => [
                    'errorDetails' => __('general.failed'),
                ],
            ];
        }
    }

    /**
     * Retrieve user data including ratings and item counts.
     *
     * @param int $id The user ID whose data needs to be retrieved.
     * @return array Response containing status and user data.
     */


public function getUserData($id)
{
    try {
        $user = User::select(['id', 'name'])
            ->withCount(['ratings as countRatings', 'items as countItems'])
            ->addSelect([
                DB::raw("CASE WHEN EXISTS (
                    SELECT 1 FROM users_users 
                    WHERE users_users.favorite_user_id = {$id} 
                      AND users_users.user_id = " . (int)auth()->id() . "
                ) THEN 1 ELSE 0 END AS is_favourite")
            ])
            ->findOrFail($id);

        return [
            'status'  => 200,
            'message' => __('user.get_successful'),
            'data'    => $user,
        ];
    } catch (Exception $e) {
        // Log error for debugging
        Log::error('Error in getUserData: ' . $e->getMessage());

        return [
            'status'  => 500,
            'message' => [
                'errorDetails' => __('general.failed'),
            ],
        ];
    }
}

    /**
     * Retrieve saved items of the authenticated user.
     *
     * @return array Response containing status and saved items data.
     */
    public function getSavedItems()
    {
        try {
            // Get the authenticated user
            $user = auth()->user();

            // Retrieve saved items along with a single photo for each
            $items = $user->savedItems()->with(['photos' => function ($query) {
                $query->select('id', 'url', 'photoable_id')->limit(1);
            }])->get();

            return [
                'status'  => 200,
                'message' => __('item.get_successful'),
                'data'    => $items,
            ];
        } catch (Exception $e) {
            // Log error for debugging
            Log::error('Error in getSavedItems: ' . $e->getMessage());

            return [
                'status' => 500,
                'message' => [
                    'errorDetails' => __('general.failed'),
                ],
            ];
        }
    }

    /**
     * Retrieve favorite users of the authenticated user.
     *
     * @return array Response containing status and favorite users data.
     */
    public function getFavouriteUsers()
    {
        try {
            // Get the authenticated user
            $user = auth()->user();

            // Retrieve favorite users along with a single photo for each
            $favoriteUsers = $user->favoriteUsers()->with(['photo' => function ($query) {
                $query->select('id', 'url', 'photoable_id')->limit(1);
            }])->get();

            return [
                'status'  => 200,
                'message' => __('user.get_successful'),
                'data'    => $favoriteUsers,
            ];
        } catch (\Throwable $e) {
            // Log error for debugging
            Log::error('Error in getFavouriteUsers: ' . $e->getMessage());

            return [
                'status' => 500,
                'message' => [
                    'errorDetails' => __('general.failed'),
                ],
            ];
        }
    }
}
