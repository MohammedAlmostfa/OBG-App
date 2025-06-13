<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ForgetPasswordController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\SavedItemController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\Usercontroller;

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

    Route::apiResource('profile', ProfileController::class);


    Route::get('items/user/{id}', [Usercontroller::class, 'getUserItems']);
    Route::get('ratings/user/{id}', [Usercontroller::class, 'getUserRatings']);
    Route::get('user/{id}', [Usercontroller::class, 'getUserData']);
    Route::get('savedItems', [Usercontroller::class, 'getSavedItems']);
    Route::apiResource('ratings', RatingController::class);

    Route::get('/me', [ProfileController::class, 'getme']); // Retrieves details of the logged-in user
    Route::post('items/{id}/save', [SavedItemController::class, 'save']);
    Route::delete('items/{id}/unsave', [SavedItemController::class, 'unSave']);
    Route::apiResource('items', ItemController::class);
});
