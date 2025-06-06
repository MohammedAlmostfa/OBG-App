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
     * The CountryService instance.
     *
     * @var itemService
     */
    private $ItemS;

    /**
     * Create a new CountryController instance.
     *
     * @param itemService $itemService
     */
    public function __construct(ItemService $itemService)
    {
        $this->itemService = $itemService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreItemData $request)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateItemData $request, Item $item)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        //
    }
}
