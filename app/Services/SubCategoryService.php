<?php

namespace App\Services;

use Exception;
use App\Models\SubCategory;
use Illuminate\Support\Facades\Log;

class SubCategoryService
{
    public function getSubCategories($categoryId)
    {
        try {
            // Fetch subcategories based on category_id
            $subCategories = SubCategory::select('name', 'id')
                ->where('category_id', $categoryId)
                ->get();

            // Return the list of subcategories with a success message
            return [
                'message' => 'Subcategories retrieved successfully',
                'status' => 200,
                'data' => $subCategories,
            ];
        } catch (Exception $e) {
            // Log the error if an exception occurs
            Log::error('Error in getSubCategories: ' . $e->getMessage());

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
