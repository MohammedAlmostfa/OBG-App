<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Item;
use App\Models\User;
use App\Models\Category;
use App\Models\SubCategory;

class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition()
    {
        return [
            'user_id' => User::first(),
            'category_id' => $this->faker->numberBetween(1, 2),
            'subCategory_id' => $this->faker->numberBetween(1, 2),
            'name' => $this->faker->word(),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'type' => $this->faker->randomElement([1, 0]),
            'description' => $this->faker->paragraph(),
            'details' => $this->faker->text(200),
        ];
    }
}
