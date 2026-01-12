<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Xóa bảng cũ nếu tồn tại
        Schema::dropIfExists('transactions');
        
        // Tạo lại với đầy đủ cột
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('wallet_id');
            $table->decimal('so_tien', 15, 2);
            $table->enum('loai_giao_dich', ['THU','CHI']);
            $table->date('ngay_giao_dich');
            $table->text('ghi_chu')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('CASCADE');

            $table->foreign('category_id')
                  ->references('id')->on('categories')
                  ->onDelete('CASCADE');

            $table->foreign('wallet_id')
                  ->references('id')->on('wallets')
                  ->onDelete('CASCADE');

            // Indexes
            $table->index(['user_id', 'ngay_giao_dich']);
            $table->index('loai_giao_dich');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
