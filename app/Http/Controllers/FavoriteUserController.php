<?php

namespace App\Http\Controllers;

use App\Services\FavoriteUserService;
use Illuminate\Http\Request;

class FavoriteUserController extends Controller
{
    /**
     * Service handling favorite user operations.
     *
     * @var FavoriteUserService
     */
    private $favoriteUserService;

    /**
     * FavoriteUserController constructor.
     * 
     * @param FavoriteUserService $favoriteUserService Handles favorite user operations.
     */
    public function __construct(FavoriteUserService $favoriteUserService)
    {
        $this->favoriteUserService = $favoriteUserService;
    }

    /**
     * Add a user to the authenticated user's favorites list.
     * 
     * @param int $id The ID of the user to be added as a favorite.
     * @return \Illuminate\Http\JsonResponse JSON response indicating success or failure.
     */
    public function add($id)
    {
        $result = $this->favoriteUserService->addToFavourite($id);

        return $result['status'] === 200
            ? self::success(null, $result['message'], $result['status'])
            : self::error(null, $result['message'], $result['status']);
    }

    /**
     * Remove a user from the authenticated user's favorites list.
     * 
     * @param int $id The ID of the user to be removed from favorites.
     * @return \Illuminate\Http\JsonResponse JSON response indicating success or failure.
     */
    public function remove($id)
    {
        // Ensure authorization is applied correctly
        $this->authorize('remove', $id);

        $result = $this->favoriteUserService->removeFromFavourite($id);

        return $result['status'] === 200
            ? self::success(null, $result['message'], $result['status'])
            : self::error(null, $result['message'], $result['status']);
    }
}
