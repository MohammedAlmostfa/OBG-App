<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
            ['en' => 'Saudi Arabia', 'ar' => 'السعودية'],
            ['en' => 'United Arab Emirates', 'ar' => 'الإمارات'],
            ['en' => 'Qatar', 'ar' => 'قطر'],
            ['en' => 'Bahrain', 'ar' => 'البحرين'],
            ['en' => 'Kuwait', 'ar' => 'الكويت'],
            ['en' => 'Oman', 'ar' => 'عُمان'],
            ['en' => 'Yemen', 'ar' => 'اليمن'],
            ['en' => 'Iraq', 'ar' => 'العراق'],
            ['en' => 'Jordan', 'ar' => 'الأردن'],
            ['en' => 'Lebanon', 'ar' => 'لبنان'],
            ['en' => 'Syria', 'ar' => 'سوريا'],
            ['en' => 'Palestine', 'ar' => 'فلسطين'],
            ['en' => 'Egypt', 'ar' => 'مصر'],
            ['en' => 'Iran', 'ar' => 'إيران'],
            ['en' => 'Turkey', 'ar' => 'تركيا'],
            ['en' => 'Cyprus', 'ar' => 'قبرص']
        ];

        foreach ($countries as $country) {
            Country::create([
                'name' => json_encode($country),
            ]);
        }
    }
}
