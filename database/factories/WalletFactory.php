<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Wallet;
use App\Models\User;

class WalletFactory extends Factory
{
    protected $model = Wallet::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(), 
            'ten_vi' => $this->faker->word(), 
            'so_du' => $this->faker->randomFloat(2, 0, 1000000), 
        ];
    }
}

