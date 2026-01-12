<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->enum('phuong_thuc_thanh_toan', ['Tiền mặt', 'Chuyển khoản'])
                  ->default('Tiền mặt')
                  ->after('loai_giao_dich')
                  ->comment('Phương thức thanh toán: tiền mặt hoặc chuyển khoản');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('phuong_thuc_thanh_toan');
        });
    }
};