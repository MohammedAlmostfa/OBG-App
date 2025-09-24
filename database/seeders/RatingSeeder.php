<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rating;
use App\Models\User;

class RatingSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            // كل مستخدم يقيم 2 مستخدمين آخرين عشوائياً
            $otherUsers = $users->where('id', '!=', $user->id)->random(2);

            foreach ($otherUsers as $ratedUser) {
                Rating::create([
                    'user_id' => $user->id,
                    'rated_user_id' => $ratedUser->id,
                    'rate' => rand(1, 5),
                    'review' => 'This is a sample review from user ' . $user->id,
                ]);
            }
        }
    }
}
