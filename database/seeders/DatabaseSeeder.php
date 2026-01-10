<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Tạo 5 user
        $users = User::factory(5)->create();

        // Tạo 10 danh mục
        $categories = Category::factory(10)->create();

        // Tạo 2 ví cho từng user
        foreach ($users as $user) {
            Wallet::factory(2)->create([
                'user_id' => $user->id,
            ]);
        }

        // Tạo 50 giao dịch ngẫu nhiên
        Transaction::factory(50)->create();
    }
}

