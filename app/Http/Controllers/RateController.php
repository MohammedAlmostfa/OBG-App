<?php

namespace App\Http\Controllers;

use App\Http\Requests\RateRequest\StoreRateData;
use App\Http\Requests\RateRequest\UpdateRateData;
use App\Models\Rate;
use App\Services\RateService;
use Illuminate\Http\JsonResponse;

class RateController extends Controller
{
    /**
     * The RateService instance.
     *
     * @var RateService
     */
    private $rateService;

    /**
     * Inject the RateService into the controller.
     *
     * @param RateService $rateService
     */
    public function __construct(RateService $rateService)
    {
        $this->rateService = $rateService;
    }

    /**
     * Display a listing of rates. (to be implemented)
     *
     * @return JsonResponse
     */
    public function index()
    {
        // Not implemented yet
        return self::success([], 'Rate list not implemented yet', 200);
    }

    /**
     * Store a newly created rate in storage.
     *
     * @param StoreRateData $request
     * @return JsonResponse
     */
    public function store(StoreRateData $request)
    {
        $validatedData = $request->validated();

        $result = $this->rateService->storeRate($validatedData);

        return $result['status'] === 200
            ? self::success(null, $result['message'], $result['status'])
            : self::error(null, $result['message'], $result['status']);
    }

    /**
     * Update the specified rate in storage.
     *
     * @param UpdateRateData $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateRateData $request, $id)
    {
        $validatedData = $request->validated();

        $rate = Rate::findOrFail($id);

        $result = $this->rateService->updateRate($rate, $validatedData);

        return $result['status'] === 200
            ? self::success(null, $result['message'], $result['status'])
            : self::error(null, $result['message'], $result['status']);
    }

    /**
     * Soft delete the specified rate.
     *
     * @param Rate $rate
     * @return JsonResponse
     */
    public function destroy(Rate $rate)
    {
        $result = $this->rateService->deleteRate($rate);

        return $result['status'] === 200
            ? self::success(null, $result['message'], $result['status'])
            : self::error(null, $result['message'], $result['status']);
    }

    
}
