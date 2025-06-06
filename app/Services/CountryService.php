<?php

namespace App\Services;

use Exception;
use App\Models\Country;
use Illuminate\Support\Facades\Log;

class CountryService
{
    /**
     * Retrieve all countries.
     *
     * This method fetches all countries from the database and returns them in a structured response.
     *
     * @return array Contains message, data (list of countries), and status.
     */
    public function getCountries()
    {
        try {

            $cities = Country::select('name', 'id')->get();
            // Return the list of cities with a success message
            return [
                'message' => 'Cities retrieved successfully',
                'status' => 200,
                'data' => $cities,
            ];
        } catch (Exception $e) {
            // Log the error if an exception occurs
            Log::error('Error in getCities: ' . $e->getMessage());

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
