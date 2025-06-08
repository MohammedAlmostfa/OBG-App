<?php 

namespace App\Services;

use App\Models\Rate;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * Class RateService
 * Handles the business logic related to rating functionality.
 */
class RateService
{
    /**
     * Store a new rating in the database.
     *
     * @param array $data Validated request data.
     * @return array Response status and message.
     */
    public function storeRate($data)
    {
        try {
            // Create new rate record
            $rate = Rate::create([
                'user_id'       => auth()->user()->id,           // Authenticated user
                'rate'          => $data["rate"],                // Rating value (1-5)
                'review'        => $data["review"],              // Optional text review
                'rated_user_id' => $data["rated_user_id"],       // ID of the rated user
            ]);

            return [
                'status'  => 200,
                'message' => __('rate.create_successful'),
            ];
        } catch (Exception $e) {
            // Log and return generic error message
            Log::error('Error in storeRate: ' . $e->getMessage());

            return [
                'status'  => 500,
                'message' => __('general.failed'),
            ];
        }
    }

    /**
     * Update an existing rate record.
     *
     * @param Rate $rate The rate model instance to update.
     * @param array $data Data to update the rate with.
     * @return array Response status and message.
     */
    public function updateRate(Rate $rate, $data)
    {
        try {
            // Update only provided fields
            $rate->update([
                'rate'   => $data["rate"] ?? $rate->rate,
                'review' => $data["review"] ?? $rate->review,
            ]);

            return [
                'status'  => 200,
                'message' => __('rate.update_successful'),
            ];
        } catch (Exception $e) {
            // Log and return error
            Log::error('Error in updateRate: ' . $e->getMessage());

            return [
                'status'  => 500,
                'message' => __('general.failed'),
            ];
        }
    }

    /**
     * Soft delete a rate record.
     *
     * @param Rate $rate The rate model instance to delete.
     * @return array Response status and message.
     */
    public function deleteRate(Rate $rate)
    {
        try {
            $rate->delete(); // Uses soft delete

            return [
                'status'  => 200,
                'message' => __('rate.delete_successful'),
            ];
        } catch (Exception $e) {
            Log::error('Error in deleteRate: ' . $e->getMessage());

            return [
                'status'  => 500,
                'message' => __('general.failed'),
            ];
        }
    }
}
