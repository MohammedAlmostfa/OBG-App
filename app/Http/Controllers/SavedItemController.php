<?php

namespace App\Http\Controllers;

use App\Services\SavedItemService;

/**
 * Class SavedItemController
 *
 * Controller for managing saved items.
 */
class SavedItemController extends Controller
{
    /**
     * @var SavedItemService
     */
    private $savedItemService;

    /**
     * SavedItemController constructor.
     * 
     * @param SavedItemService $savedItemService Handles saved item operations.
     */
    public function __construct(SavedItemService $savedItemService)
    {
        $this->savedItemService = $savedItemService;
    }

    /**
     * Save an item.
     * 
     * @param int $id The ID of the item to be saved.
     * @return \Illuminate\Http\JsonResponse JSON response indicating success or failure.
     */
    public function save($id)
    {
        $result = $this->savedItemService->saveItem($id);

        return $result['status'] === 200
            ? self::success(null, $result['message'], $result['status'])
            : self::error(null, $result['message'], $result['status']);
    }

    /**
     * Unsave an item.
     * 
     * @param int $id The ID of the item to be unsaved.
     * @return \Illuminate\Http\JsonResponse JSON response indicating success or failure.
     */
    public function unSave($id)
    {$this->authorize('unSave', $id);

        $result = $this->savedItemService->unsaveItem($id);

        return $result['status'] === 200
            ? self::success(null, $result['message'], $result['status'])
            : self::error(null, $result['message'], $result['status']);
    }
}
