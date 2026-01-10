<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'ten_danh_muc' => $this->faker->word(), 
            'loai_danh_muc' => $this->faker->randomElement(['THU','CHI']), 
            'danh_muc_cha_id' => null, 
            'bieu_tuong' => null, 
            'mo_ta' => $this->faker->sentence(), 
        ];
    }
}

