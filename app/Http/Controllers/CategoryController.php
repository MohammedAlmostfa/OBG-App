<?php

namespace App\Http\Controllers;

use App\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    /**
     * The CategoryService instance.
     *
     * @var CategoryService
     */
    private $categoryService;

    /**
     * Create a new CategoryController instance.
     *
     * @param CategoryService $categoryService
     */
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Display a listing of all categories.
     *
     * This method retrieves all categories using the CategoryService and returns a JSON response.
     *
     * @return JsonResponse
     */
    public function index()
    {
        // Retrieve categories using the CategoryService
        $result = $this->categoryService->getCategories();

        // Return a structured response
        return $result['status'] === 200
        ? self::success($result['data'], $result['message'], $result['status'])
        : self::error(null, $result['message'], $result['status']);

    }
}
