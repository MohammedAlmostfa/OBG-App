<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Profile;
use App\Models\User;

class ProfileSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            Profile::create([
                'user_id' => $user->id,
                'birthday' => now()->subYears(rand(18, 50)),
                'phone' => '09' . rand(10000000, 99999999),
                'address' => 'Address ' . $user->id,
                'latitude' => 33.5 + rand(-50, 50)/100,  // قيم تقريبية
                'longitude' => 36.3 + rand(-50, 50)/100,
            ]);
        }
    }
}
