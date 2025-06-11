<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\UserService;

class UserController extends Controller
{
    /**
     * UserService instance to handle business logic.
     *
     * @var UserService
     */
    private $userService;

    /**
     * Constructor: Inject the UserService dependency.
     *
     * @param UserService $userService Service layer for user-related functionality.
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Retrieve a list of items for a specific user.
     *
     * @param int $id The ID of the user whose items are being fetched.
     * @return JsonResponse Response with retrieved item data.
     */
    public function getUserItems($id)
    {
        // Fetch filtered items via service
        $result = $this->userService->getUserItems($id);

        // Return appropriate JSON response
        return $result['status'] === 200
            ? self::success($result['data'], $result['message'], $result['status'])
            : self::error(null, $result['message'], $result['status']); 
    }

    /**
     * Retrieve a list of ratings for a specific user.
     *
     * @param int $id The ID of the user whose ratings are being fetched.
     * @return JsonResponse Response with retrieved rating data.
     */
    public function getUserRatings($id)
    {
        // Fetch user ratings via service
        $result = $this->userService->getUserRating($id);

        return $result['status'] === 200
            ? self::success($result['data'], $result['message'], $result['status'])
            : self::error(null, $result['message'], $result['status']);
    }
     public function getUserData($id)
    {
        // Fetch user ratings via service
        $result = $this->userService->getUserData($id);

        return $result['status'] === 200
            ? self::success($result['data'], $result['message'], $result['status'])
            : self::error(null, $result['message'], $result['status']);
    }
}
