<?php

namespace App\Services;

use App\Models\Province;
use Illuminate\Support\Facades\Log;
use Exception;

class ProvinceService
{
    /**
     * Retrieve all provinces for a given country.
     *
     * This method fetches all provinces from the database and returns them in a structured response.
     *
     * @param int $id The country ID
     * @return array Contains message, data (list of provinces), and status.
     */
    public function getProvinces($id)
    {
        try {
            // Fetch provinces based on country_id
            $provinces = Province::select('name', 'id')->where('country_id', $id)->get();

            // Return the list of provinces with a success message
            return [
                'message' => 'Provinces retrieved successfully',
                'status' => 200,
                'data' => $provinces,
            ];
        } catch (Exception $e) {
            // Log the error if an exception occurs
            Log::error('Error in getProvinces: ' . $e->getMessage());

            // Return an error message and status
            return [
                'status' => 500,
                'message' => [
                    'errorDetails' => __('general.failed'),
                ],
            ];
        }
    }
}
