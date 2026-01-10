<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Category;
use App\Models\Wallet;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'wallet_id' => Wallet::factory(),
            'so_tien' => $this->faker->randomFloat(2, 1000, 1000000), 
            'loai_giao_dich' => $this->faker->randomElement(['THU','CHI']), 
            'ngay_giao_dich' => $this->faker->date(), 
            'ghi_chu' => $this->faker->sentence(), 
        ];
    }
}
