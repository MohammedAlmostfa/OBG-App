<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemRequest\FilteringData;
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
     * @param FilteringData $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(FilteringData $request)
    {
        // Validate incoming request data
        $validatedData = $request->validated();

        // Fetch filtered items via service
        $result = $this->itemService->getAllItems($validatedData);

        // Return appropriate JSON response
        return $result['status'] === 200
            ? self::success($result['data'], $result['message'], $result['status'])
            : self::error(null, $result['message'], $result['status']);
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

        // Delegate creation to the service
        $result = $this->itemService->storeItem($validatedData);

        // Return appropriate JSON response
        return $result['status'] === 200
            ? self::success(null, $result['message'], $result['status'])
            : self::error(null, $result['message'], $result['status']);
    }

    /**
     * Update the specified item in storage.
     *
     * @param UpdateItemData $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateItemData $request, $id)
    {
        // Validate incoming request data
        $validatedData = $request->validated();

        // Delegate update to the service
        $result = $this->itemService->updateItem($id, $validatedData);

        // Return appropriate JSON response
        return $result['status'] === 200
            ? self::success(null, $result['message'], $result['status'])
            : self::error(null, $result['message'], $result['status']);
    }

    /**
     * Soft delete the specified item (move to trash).
     *
     * @param Item $item
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Item $item)
    {
        // Delegate soft deletion to the service
        $result = $this->itemService->softDeleteItem($item);

        // Return appropriate JSON response
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
        // Call service to permanently delete the item
        $result = $this->itemService->forceDeleteItem($item->id);

        // Return appropriate JSON response
        return $result['status'] === 200
            ? self::success(null, $result['message'], $result['status'])
            : self::error(null, $result['message'], $result['status']);
    }

    /**
     * Display the specified item details.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        // Fetch single item details
        $result = $this->itemService->getItemData($id);

        // Return structured JSON response
        return response()->json([
            'status' => $result['status'] === 200 ? 'success' : 'error',
            'message' => $result['message'],
            'data' => $result['data'] ?? null,
        ], $result['status']);
    }
}
