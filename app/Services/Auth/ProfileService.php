<?php

namespace App\Services\Auth;

use Exception;
use App\Models\Profile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ProfileService
{
    public function createProfile(array $data): array
    {
        try {
            $user = Auth::user();

            if (!$user->profile) {
                $user->name = $data['name'];
                $user->save();

                Profile::create([
                    'gender' => $data['gender'] ?? null,
                    'birthday' => $data['birthday'] ?? null,
                    'phone' => $data['phone'],
                    'address' => $data['address'],
                    'user_id' => $user->id,
                    'province_id' => $data['province_id'],
                    'country_id' => $data['country_id'],
                ]);
                if (isset($data['photo']) && $data['photo']) {
                    $image = $data['photo'];
                    $imageName = Str::random(32) . '.' . $image->getClientOriginalExtension();
                    $path = $image->storeAs('users/photos', $imageName, 'public');
                    $user->photo()->create(['url' => $path]);
                }


                return [
                    'message' => __('profile.profile_created_successfully'),
                    'status' => 200,
                ];
            }

            return [
                'status' => 400,
                'message' => [
                    'errorDetails' => [__('profile.user_already_has_profile')],
                ],
            ];
        } catch (Exception $e) {
            Log::error('Error occurred while creating profile: ' . $e->getMessage());

            return [
                'status' => 500,
                'message' => [
                    'errorDetails' => __('general.failed'),
                ],
            ];
        }
    }

    public function updateProfile(array $data): array
    {
        try {
            $user = Auth::user();
            $profile = $user->profile;

            if (!empty($data['name'])) {
                $user->name = $data['name'];
                $user->save();
            }

            if ($profile) {
                $profile->update([
                    'gender' => $data['gender'] ?? $profile->gender,
                    'birthday' => $data['birthday'] ?? $profile->birthday,
                    'phone' => $data['phone'] ?? $profile->phone,
                    'address' => $data['address'] ?? $profile->address,
                    'province_id' => $data['province_id'] ?? $profile->province_id,
                    'country_id' => $data['country_id'] ?? $profile->country_id,
                ]);

                if (isset($data['photo']) && $data['photo']) {
                    $user->photo()->delete(); // Corrected
                    $image = $data['photo'];
                    $imageName = Str::random(32) . '.' . $image->getClientOriginalExtension();
                    $path = $image->storeAs('users/photos', $imageName, 'public');
                    $user->photo()->create(['url' => $path]);
                }



                return [
                    'message' => __('profile.profile_updated_successfully'),
                    'status' => 200,
                ];
            }

            return [
                'status' => 404,
                'message' => [
                    'errorDetails' => [__('profile.user_does_not_have_profile')],
                ],
            ];
        } catch (Exception $e) {
            Log::error('Error occurred while updating profile: ' . $e->getMessage());

            return [
                'status' => 500,
                'message' => [
                    'errorDetails' => __('general.failed'),
                ],
            ];
        }
    }

    public function getMe(): array
    {
        try {
            $user = Auth::user();
            $user->load(['profile.country', 'profile.province']);

            $userData = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'gender' => $user->profile->gender,
                'birthday' => $user->profile->birthday,
                'phone' => $user->profile->phone,
                'address' => $user->profile->address,
                'country_name' => json_decode($user->profile->country->name, true) ?? null,
                'province_name' => json_decode($user->profile->province->name, true) ?? null,
            ];

            return [
                'message' => __('profile.user_data_retrieved_successfully'),
                'status' => 200,
                'data' => $userData,
            ];
        } catch (Exception $e) {
            Log::error('Error occurred while retrieving user data: ' . $e->getMessage());

            return [
                'status' => 500,
                'message' => [
                    'errorDetails' => __('general.failed'),
                ],
            ];
        }
    }
}
