<?php

namespace Database\Seeders;

use App\Models\Province;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $provinces = [
            ['en' => 'Damascus', 'ar' => 'دمشق'],
            ['en' => 'Aleppo', 'ar' => 'حلب'],
            ['en' => 'Homs', 'ar' => 'حمص'],
            ['en' => 'Hama', 'ar' => 'حماة'],
            ['en' => 'Latakia', 'ar' => 'اللاذقية'],
            ['en' => 'Tartous', 'ar' => 'طرطوس'],
            ['en' => 'Idlib', 'ar' => 'إدلب'],
            ['en' => 'Deir ez-Zor', 'ar' => 'دير الزور'],
            ['en' => 'Raqqa', 'ar' => 'الرقة'],
            ['en' => 'Hasakah', 'ar' => 'الحسكة'],
            ['en' => 'Daraa', 'ar' => 'درعا'],
            ['en' => 'Suwayda', 'ar' => 'السويداء'],
            ['en' => 'Quneitra', 'ar' => 'القنيطرة']
        ];
        foreach ($provinces as $provinces) {
            Province::create([
                'name' => json_encode($provinces),
                'country_id'=>'1',

            ]);
        }
    }
}
