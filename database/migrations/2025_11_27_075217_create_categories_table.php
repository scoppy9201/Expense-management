<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Bảng danh mục giao dịch 
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('ten_danh_muc');
            $table->enum('loai_danh_muc', ['THU','CHI']);
            $table->unsignedBigInteger('danh_muc_cha_id')->nullable();
            $table->string('bieu_tuong', 50)->nullable();
            $table->text('mo_ta')->nullable();
            $table->timestamps();

            $table->foreign('danh_muc_cha_id')
                  ->references('id')->on('categories')
                  ->onDelete('CASCADE');

            $table->index('loai_danh_muc');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};

