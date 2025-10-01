<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ItemRequest\FilteringData;
use App\Http\Requests\ItemRequest\StoreItemData;
use App\Http\Requests\ItemRequest\UpdateItemData;
use App\Http\Resources\ItemDetailsResource;
use App\Http\Resources\ItemResource;
use App\Models\Item;
use App\Services\ItemService;

/**
 * Class ItemController
 *
 * Handles CRUD operations and listing for Item resources.
 *
 * @package App\Http\Controllers
 */
class ItemController extends Controller
{
    /**
     * The ItemService instance for business logic.
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
     * Display a combined listing of items:
     * - Latest items
     * - Nearby items (based on authenticated user's location)
     * - Lowest-priced items
     *
     * Returns all three sets. If one fails, it will return an empty array
     * and a message describing the failure.
     *
     * @param FilteringData $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(FilteringData $request)
    {
        // Validate incoming request data
        $validatedData = $request->validated();

        // Fetch data from service layer
        $lastItems   = $this->itemService->getLastestItems($validatedData);
        $nearItems   = $this->itemService->getNearestItems();
        $lowestItems = $this->itemService->getLowestItems();

        // Build response structure
        $data = [
            'lastest_items'   => $lastItems['status'] === 200
                ? ItemResource::collection($lastItems['data'])
                : [],
            'nearest_items'   => $nearItems['status'] === 200
                ? ItemResource::collection($nearItems['data'])
                : [],
            'lowest_items' => $lowestItems['status'] === 200
                ? ItemResource::collection($lowestItems['data'])
                : [],
        ];

        // Collect error messages for failed sections
        $messages = [];
        if ($lastItems['status'] !== 200) {
            $messages['lastest_items'] = $lastItems['message'];
        }
        if ($nearItems['status'] !== 200) {
            $messages['nearest_items'] = $nearItems['message'];
        }
        if ($lowestItems['status'] !== 200) {
            $messages['lowest_items'] = $lowestItems['message'];
        }

        // Return structured JSON response
        return self::success([
            'data'     => $data,
            'messages' => $messages,
        ], __('item.get_successful'), 200);
    }

    /**
     * Get only the latest items with optional filtering.
     *
     * @param FilteringData $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLastestItems(FilteringData $request)
    {
        $validatedData = $request->validated();
        $result = $this->itemService->getLastestItems($validatedData);

        return $result['status'] === 200
            ? self::paginated($result['data'], ItemResource::class, $result['message'], $result['status'])
            : self::error(null, $result['message'], $result['status']);
    }

    /**
     * Get the lowest-priced items.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLowestItem()
    {
        $result = $this->itemService->getLowestItems();

        return $result['status'] === 200
            ? self::paginated($result['data'], ItemResource::class, $result['message'], $result['status'])
            : self::error(null, $result['message'], $result['status']);
    }

    /**
     * Get items near the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNearestItems()
    {
        $result = $this->itemService->getNearestItems();

        return $result['status'] === 200
            ? self::paginated($result['data'], ItemResource::class, $result['message'], $result['status'])
            : self::error(null, $result['message'], $result['status']);
    }

    public function getItemBySearch()
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
        $validatedData = $request->validated();
        $result = $this->itemService->storeItem($validatedData);

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
        $validatedData = $request->validated();
        $result = $this->itemService->updateItem($id, $validatedData);

        return $result['status'] === 200
            ? self::success(null, $result['message'], $result['status'])
            : self::error(null, $result['message'], $result['status']);
    }

    /**
     * Soft delete the specified item (move it to trash).
     *
     * @param Item $item
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Item $item)
    {
        $result = $this->itemService->softDeleteItem($item);

        return $result['status'] === 200
            ? self::success(null, $result['message'], $result['status'])
            : self::error(null, $result['message'], $result['status']);
    }

    /**
     * Permanently delete the specified item from storage.
     *
     * @param Item $item
     * @return \Illuminate\Http\JsonResponse
     */
    public function forceDestroy(Item $item)
    {
        $result = $this->itemService->forceDeleteItem($item->id);

        return $result['status'] === 200
            ? self::success(null, $result['message'], $result['status'])
            : self::error(null, $result['message'], $result['status']);
    }

    /**
     * Display detailed information for a specific item.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $result = $this->itemService->getItemData($id);

        return $result['status'] === 200
            ? self::success(new ItemDetailsResource($result['data']), $result['message'], $result['status'])
            : self::error(null, $result['message'], $result['status']);
    }
}
