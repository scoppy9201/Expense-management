<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('ten_danh_muc');
            $table->enum('loai_danh_muc', ['THU', 'CHI']);
            $table->unsignedBigInteger('danh_muc_cha_id')->nullable();
            $table->string('bieu_tuong', 100)->default('money.png');
            $table->text('mo_ta')->nullable();
            $table->boolean('trang_thai')->default(true);
            $table->timestamps();

            // Foreign keys
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('CASCADE');

            $table->foreign('danh_muc_cha_id')
                  ->references('id')->on('categories')
                  ->onDelete('CASCADE');

            // Indexes
            $table->index('user_id');
            $table->index('loai_danh_muc');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};