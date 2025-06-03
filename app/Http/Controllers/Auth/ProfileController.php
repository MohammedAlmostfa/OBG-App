<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Auth\ProfileService;
use App\Http\Requests\Profile\StorProfileRequest;
use App\Http\Requests\ProfileRequest\StoreProfileRequest;
use App\Http\Requests\ProfileRequest\UpdateProfileRequest;

/**
 * Class ProfileController
 *
 * Handles profile-related operations for authenticated users.
 */
class ProfileController extends Controller
{
    protected $ProfileService;

    /**
     * Constructor to inject the ProfileService dependency.
     *
     * @param ProfileService $ProfileService The profile service instance.
     */
    public function __construct(ProfileService $ProfileService)
    {
        $this->ProfileService = $ProfileService;
    }

    /**
     * Store a new profile for the authenticated user.
     *
     * @param StorProfileRequest $request The validated request data.
     * @return \Illuminate\Http\JsonResponse The JSON response.
     */
    public function store(StoreProfileRequest $request)
    {
        // Validate and retrieve request data
        $credentials = $request->validated();

        // Use the ProfileService to create a new profile
        $result = $this->ProfileService->createProfile($credentials);

        // Return appropriate response based on the result
        return $result['status'] === 200
            ? self::success(null, $result['message'], $result['status'])
            : self::error(null, $result['message'], $result['status']);
    }

    /**
     * Update the profile of the authenticated user.
     *
     * @param UpdateProfileRequest $request The validated request data.
     * @return \Illuminate\Http\JsonResponse The JSON response.
     */
    public function update(UpdateProfileRequest $request)
    {
        // Validate and retrieve request data
        $credentials = $request->validated();

        // Use the ProfileService to update the profile
        $result = $this->ProfileService->updateProfile($credentials);

        // Return appropriate response based on the result
        return $result['status'] === 200
            ? self::success(null, $result['message'], $result['status'])
            : self::error(null, $result['message'], $result['status']);
    }

    /**
     * Retrieve authenticated user's data.
     *
     * @return \Illuminate\Http\JsonResponse The JSON response.
     */
    public function getme()
    {
        // Use the ProfileService to get user's data
        $result = $this->ProfileService->getme();

        // Return appropriate response based on the result
        return $result['status'] === 200
            ? self::success($result['data'], $result['message'], $result['status'])
            : self::error(null, $result['message'], $result['status']);
    }


}
