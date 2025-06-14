<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserRatingResource;
use App\Models\User;
use App\Services\UserService;
use App\Http\Resources\ItemResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;

/**
 * Class UserController
 *
 * Handles user-related operations such as retrieving items, ratings, saved items, and favorite users.
 */
class UserController extends Controller
{
    /**
     * Instance of UserService to handle business logic.
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
    public function getUserItems($id): JsonResponse
    {
        // Fetch filtered items via service layer
        $result = $this->userService->getUserItems($id);

        // Return appropriate JSON response
        return $result['status'] === 200
            ? self::success(ItemResource::collection(collect($result['data'])), $result['message'], $result['status'])
            : self::error(null, $result['message'], $result['status']);
    }

    /**
     * Retrieve a list of ratings for a specific user.
     *
     * @param int $id The ID of the user whose ratings are being fetched.
     * @return JsonResponse Response with retrieved rating data.
     */
    public function getUserRatings($id): JsonResponse
    {
        // Fetch user ratings via service
        $result = $this->userService->getUserRating($id);

        // Return appropriate JSON response
        return $result['status'] === 200
            ? self::success(UserRatingResource::collection(collect($result['data'])), $result['message'], $result['status'])
            : self::error(null, $result['message'], $result['status']);
    }

    /**
     * Retrieve detailed user data.
     *
     * @param int $id The ID of the user whose data is being fetched.
     * @return JsonResponse Response with user data.
     */
    public function getUserData($id): JsonResponse
    {
        // Fetch user data via service layer
        $result = $this->userService->getUserData($id);

        // Return appropriate JSON response
        return $result['status'] === 200
            ? self::success($result['data'], $result['message'], $result['status'])
            : self::error(null, $result['message'], $result['status']);
    }

    /**
     * Retrieve saved items of the authenticated user.
     *
     * @return JsonResponse Response with saved items data.
     */
    public function getSavedItems(): JsonResponse
    {
        // Fetch saved items via service layer
        $result = $this->userService->getSavedItems();

        // Return appropriate JSON response
        return $result['status'] === 200
            ? self::success(ItemResource::collection(collect($result['data'])), $result['message'], $result['status'])
            : self::error(null, $result['message'], $result['status']);
    }

    /**
     * Retrieve favorite users of the authenticated user.
     *
     * @return JsonResponse Response with favorite users data.
     */
    public function getFavouriteUsers(): JsonResponse
    {
        // Fetch favorite users via service layer
        $result = $this->userService->getFavouriteUsers();

        // Return appropriate JSON response
        return $result['status'] === 200
            ? self::success(UserResource::collection(collect($result['data'])), $result['message'], $result['status'])
            : self::error(null, $result['message'], $result['status']);
    }
}
