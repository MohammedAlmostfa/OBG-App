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
            $categories = Category::select('id', 'name')
                ->with([
                    'subCategories:id,category_id,name',
                    'photo'
                ])
                ->get()
                ->map(function ($category) {
                    return [
                        'id' => $category->id,
                        'name' => $category->name,
                        'photo_url' => $category->photo->first()
                            ? asset('storage/' . $category->photo->first()->url)
                            : null,
                        'subCategories' => $category->subCategories,
                    ];
                });

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
