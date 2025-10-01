<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ForgetPasswordController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FavoriteUserController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\SavedItemController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\Usercontroller;
use App\Services\FavoriteUserService;

// Public routes (no authentication required)

// User login
Route::post('/login', [AuthController::class, 'login']); // Handles user login
Route::post('/loginwithGoogel', [AuthController::class, 'loginwithGoogel']); // Handles login via Google OAuth

// User registration
Route::post('/register', [AuthController::class, 'register']); // Handles user registration

// User logout
Route::post('logout', [AuthController::class, 'logout']); // Logs out the authenticated user

// Refresh JWT token
Route::post('refresh', [AuthController::class, 'refresh']); // Refreshes the JWT token

// Email verification routes
Route::post('/verify-email', [AuthController::class, 'verify']); // Verifies user's email
Route::post('/resendCode', [AuthController::class, 'resendCode']); // Resends the verification code

// Change password routes
Route::post('/changePassword', [ForgetPasswordController::class, 'changePassword']); // Handles password change
Route::post('/checkEmail', [ForgetPasswordController::class, 'checkEmail']); // Checks if the email exists for password reset
Route::post('/checkCode', [ForgetPasswordController::class, 'checkCode']); // Verifies a password reset code
Route::get('countries', [CountryController::class, "index"]);
Route::get('provinces/country/{id}', [ProvinceController::class, 'index']);

Route::get('categories', [CategoryController::class, "index"]);
Route::get('subCategories/category/{id}', [SubCategoryController::class, 'index']);


Route::middleware('jwt')->group(function () {

    // Profile routes
    Route::apiResource('profile', ProfileController::class);
    Route::get('/me', [ProfileController::class, 'getMe']);

    // Item routes
    Route::prefix('items')->group(function () {
        Route::get('nearest', [ItemController::class, 'getNearestItems'])->name('items.nearest');
        Route::get('lowest', [ItemController::class, 'getLowestItem'])->name('items.lowest'); // fixed spelling
        Route::get('lastest', [ItemController::class, 'getLastestItems'])->name('items.lastest');

        Route::get('user/{id}', [UserController::class, 'getUserItems'])->name('items.user');

        // Save / Unsave items
        Route::post('{id}/save', [SavedItemController::class, 'save'])->name('items.save');
        Route::delete('{id}/unsave', [SavedItemController::class, 'unSave'])->name('items.unsave');
        Route::get('/save', [UserController::class, 'getSavedItems']);

    });

    // User routes
    Route::prefix('users')->group(function () {
        Route::get('{id}', [UserController::class, 'getUserData'])->name('users.show');
        Route::post('{id}/add', [FavoriteUserController::class, 'add'])->name('users.addFavorite');
        Route::delete('{id}/remove', [FavoriteUserController::class, 'remove'])->name('users.removeFavorite');
        Route::get('favouriteUsers', [UserController::class, 'getFavouriteUsers'])->name('users.favourite');
    });

    // Ratings routes
    Route::apiResource('ratings', RatingController::class);
    Route::get('ratings/user/{id}', [UserController::class, 'getUserRatings'])->name('ratings.user');

    // Full CRUD for items
    Route::apiResource('items', ItemController::class);
});
