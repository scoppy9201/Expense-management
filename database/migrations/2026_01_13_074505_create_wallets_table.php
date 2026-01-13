<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('category_id');
            $table->string('ten_ngan_sach');
            $table->decimal('ngan_sach_goc', 15, 2)->default(0)->comment('Ngân sách ban đầu đã đặt');
            $table->decimal('so_du', 15, 2)->default(0);
            $table->text('mo_ta')->nullable();
            $table->boolean('trang_thai')->default(true);
            $table->timestamps();

            // Foreign keys
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('CASCADE');

            $table->foreign('category_id')
                  ->references('id')->on('categories')
                  ->onDelete('CASCADE');

            // Indexes
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};