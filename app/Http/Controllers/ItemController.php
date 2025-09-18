<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ItemRequest\FilteringData;
use App\Http\Requests\ItemRequest\StoreItemData;
use App\Http\Requests\ItemRequest\UpdateItemData;
use App\Http\Resources\ItemDataResource;
use App\Http\Resources\ItemDetailsResource;
use App\Http\Resources\ItemResource;
use App\Models\Item;
use App\Services\ItemService;
use Illuminate\Http\Request;

/**
 * Class ItemController
 *
 * Handles all CRUD operations and listing for Item resources.
 *
 * @package App\Http\Controllers
 */
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
     * Display a paginated listing of items with optional filtering.
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

        // Return paginated JSON response or error
        return $result['status'] === 200
            ? self::paginated($result['data'], ItemResource::class, $result['message'], $result['status'])
            : self::error(null, $result['message'], $result['status']);
    }

    /**
     * Display a paginated listing of items near the authenticated user.
     *
     * @param FilteringData $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function nearitem(FilteringData $request)
    {
        // Validate incoming request data
        $validatedData = $request->validated();

        // Fetch nearby items via service
        $result = $this->itemService->getNearItems($validatedData);

        // Return paginated JSON response or error
        return $result['status'] === 200
            ? self::paginated($result['data'], ItemResource::class, $result['message'], $result['status'])
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

        // Return success or error response
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

        // Return success or error response
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

        // Return success or error response
        return $result['status'] === 200
            ? self::success(null, $result['message'], $result['status'])
            : self::error(null, $result['message'], $result['status']);
    }

    /**
     * Permanently delete the specified item from the database.
     *
     * @param Item $item
     * @return \Illuminate\Http\JsonResponse
     */
    public function forceDestroy(Item $item)
    {
        // Call service to permanently delete the item
        $result = $this->itemService->forceDeleteItem($item->id);

        // Return success or error response
        return $result['status'] === 200
            ? self::success(null, $result['message'], $result['status'])
            : self::error(null, $result['message'], $result['status']);
    }

    /**
     * Display the details of a specific item along with related data.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        // Fetch single item details via service
        $result = $this->itemService->getItemData($id);

        // Return structured JSON response or error
        return $result['status'] === 200
            ? self::success(new ItemDetailsResource($result['data']), $result['message'], $result['status'])
            : self::error(null, $result['message'], $result['status']);
    }
}
