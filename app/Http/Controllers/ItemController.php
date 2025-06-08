<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use App\Services\ItemService;
use App\Http\Requests\ItemRequest\StoreItemData;
use App\Http\Requests\ItemRequest\UpdateItemData;

class ItemController extends Controller
{
    /**
     * The ItemService instance.
     *
     * @var ItemService
     */
    private $itemService;

    /**
     * Inject the ItemService into the controller.
     *
     * @param ItemService $itemService
     */
    public function __construct(ItemService $itemService)
    {
        $this->itemService = $itemService;
    }

    /**
     * Display a listing of the items.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        
    }

    /**
     * Store a newly created item in storage.
     *
     * @param StoreItemData $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreItemData $request)
    {
        // Validate incoming request data
        $validatedData = $request->validated();

        // Call the service to handle storing logic
        $result = $this->itemService->storeItem($validatedData);

        // Return response based on result status
        return $result['status'] === 200
            ? self::success(null, $result['message'], $result['status'])
            : self::error(null, $result['message'], $result['status']);
    }

    /**
     * Update the specified item in storage.
     *
     * @param UpdateItemData $request
     * @param Item $item
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateItemData $request,  $id)
    {
        // Validate incoming request data
        $validatedData = $request->validated();

        // Call the service to handle update logic
        $result = $this->itemService->updateItem($id, $validatedData);

        // Return response based on result status
        return $result['status'] === 200
            ? self::success(null, $result['message'], $result['status'])
            : self::error(null, $result['message'], $result['status']);
    }

    /**
     * Soft delete the specified item.
     *
     * @param Item $item
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Item $item)
    {
        // Call the service to handle soft deletion
        $result = $this->itemService->softDeleteItem($item);

        // Return response based on result status
        return $result['status'] === 200
            ? self::success(null, $result['message'], $result['status'])
            : self::error(null, $result['message'], $result['status']);
    }

    /**
     * Permanently delete the specified item.
     *
     * @param Item $item
     * @return \Illuminate\Http\JsonResponse
     */
    public function forceDestroy(Item $item)
    {
        // Call the service to handle permanent deletion
        $result = $this->itemService->forceDeleteItem($item);

        // Return response based on result status
        return $result['status'] === 200
            ? self::success(null, $result['message'], $result['status'])
            : self::error(null, $result['message'], $result['status']);
    }
}
