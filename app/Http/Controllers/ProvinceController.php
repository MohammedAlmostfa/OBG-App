<?php

namespace App\Http\Controllers;

use App\Services\ProvinceService;
use Illuminate\Http\Request;

class ProvinceController extends Controller
{
    /**
     * The ProvinceService instance.
     *
     * @var ProvinceService
     */
    private $provinceService;

    /**
     * Create a new ProvinceController instance.
     *
     * @param ProvinceService $provinceService
     */
    public function __construct(ProvinceService $provinceService)
    {
        $this->provinceService = $provinceService;
    }

    /**
     * Display a listing of all provinces for a given country.
     *
     * @param int $countryId
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($id)
    {
        // Retrieve provinces using the ProvinceService
        $result = $this->provinceService->getProvinces($id);

        // Return a structured response
        return $result['status'] === 200
            ? self::success($result['data'], $result['message'], $result['status'])
            : self::error(null, $result['message'], $result['status']);

    }
}
