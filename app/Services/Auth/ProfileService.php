<?php

namespace App\Services\Auth;

use Exception;
use App\Models\Profile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
   use Illuminate\Support\Facades\DB;

class ProfileService
{

public function createProfile(array $data): array
{
    try {
        return DB::transaction(function () use ($data) {
            $user = Auth::user();

            if ($user->profile) {
                return [
                    'status' => 400,
                    'message' => [
                        'errorDetails' => [__('profile.user_already_has_profile')],
                    ],
                ];
            }


            // إنشاء البروفايل
            Profile::create([
                'name'=> $data['name'],
                'birthday' => $data['birthday'] ?? null,
                'phone' => $data['phone'],
                'address' => $data['address'],
                'user_id' => $user->id,
                'longitude' => $data['longitude'] ?? null,
                'latitude' => $data['latitude'] ?? null,
            ]);

            // رفع الصورة إذا موجودة
            if (!empty($data['photo'])) {
                $image = $data['photo'];
                $imageName = Str::random(32) . '.' . $image->getClientOriginalExtension();
                $folder = 'users/photos/' . now()->format('Y-m-d');
                $path = $image->storeAs($folder, $imageName, 'public');

                $user->photo()->create(['url' => $path]);
            }

            return [
                'message' => __('profile.profile_created_successfully'),
                'status' => 200,
            ];
        });
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
        return DB::transaction(function () use ($data) {
            $user = Auth::user();
            $profile = $user->profile;

            if (!$profile) {
                return [
                    'status' => 404,
                    'message' => [
                        'errorDetails' => [__('profile.user_does_not_have_profile')],
                    ],
                ];
            }



            // تحديث البروفايل
            $profile->update([
                'name'=> $data['name'] ?? $profile->name,
                'birthday' => $data['birthday'] ?? $profile->birthday,
                'phone' => $data['phone'] ?? $profile->phone,
                'address' => $data['address'] ?? $profile->address,
                'longitude' => $data['longitude'] ?? $profile->longitude,
                'latitude' => $data['latitude'] ?? $profile->latitude,
            ]);

            // رفع الصورة الجديدة
            if (!empty($data['photo'])) {
                $user->photo()->delete();
                $image = $data['photo'];
                $imageName = Str::random(32) . '.' . $image->getClientOriginalExtension();
                $folder = 'users/photos/' . now()->format('Y-m-d');
                $path = $image->storeAs($folder, $imageName, 'public');

                $user->photo()->create(['url' => $path]);
            }

            return [
                'message' => __('profile.profile_updated_successfully'),
                'status' => 200,
            ];
        });
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
            $user->load('profile');

            $userData = [
                'id' => $user->id,
                "firstName" => $user->first_name,
                "lastName"  => $user->last_name,
                'email' => $user->email,
                'birthday' => $user->profile->birthday,
                'phone' => $user->profile->phone,
                'address' => $user->profile->address,
                'longitude' => $user->profile->longitude,
                'latitude' => $user->profile->latitude,
                'photo' => $user->photo->first()->url ?? null,

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
