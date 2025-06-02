<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ForgetPasswordController;

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
Route::apiResource('countries', CountryController::class);


Route::middleware('auth:api')->group(function () {


    // API resource routes for countries (CRUD operations)
    // Handles CRUD for countries
});
