<?php

namespace App\Services;

use App\Models\Category;
use Exception;
use Illuminate\Support\Facades\Log;

class CategoryService
{
    public function getCategories()
    {
        try {
            $categories = Category::select('name', 'id')->get();

            return [
                'message' => 'Categories retrieved successfully',
                'status' => 200,
                'data' => $categories,
            ];
        } catch (Exception $e) {
            Log::error('Error in getCategories: ' . $e->getMessage());

            return [
                'status' => 500,
                'message' => [
                    'errorDetails' => __('general.failed'),
                ],
            ];
        }
    }
}
