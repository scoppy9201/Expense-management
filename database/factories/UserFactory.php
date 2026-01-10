<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'), // mật khẩu mặc định
            'phone' => $this->faker->phoneNumber(),
            'ngay_sinh' => $this->faker->date(),
            'gioi_tinh' => $this->faker->randomElement(['nam', 'nu', 'khac']),
            'remember_token' => Str::random(10),
        ];
    }
}

