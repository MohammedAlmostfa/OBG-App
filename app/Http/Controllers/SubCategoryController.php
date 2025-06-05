<?php

namespace App\Http\Controllers;

use App\Services\SubCategoryService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SubCategoryController extends Controller
{
    /**
     * The SubCategoryService instance.
     *
     * @var SubCategoryService
     */
    private $subCategoryService;

    /**
     * Create a new SubCategoryController instance.
     *
     * @param SubCategoryService $subCategoryService
     */
    public function __construct(SubCategoryService $subCategoryService)
    {
        $this->subCategoryService = $subCategoryService;
    }

    /**
     * Display a listing of all subcategories for a given category.
     *
     * @param int $categoryId
     * @return JsonResponse
     */
    public function index($categoryId)
    {
        // Retrieve subcategories using SubCategoryService
        $result = $this->subCategoryService->getSubCategories($categoryId);

        // Return a structured response
        return $result['status'] === 200
            ? self::success($result['data'], $result['message'], $result['status'])
            : self::error(null, $result['message'], $result['status']);

    }
}
