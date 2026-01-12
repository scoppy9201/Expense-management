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
        if (Schema::hasColumn('wallets', 'loai_ngan_sach')) {
            Schema::table('wallets', function (Blueprint $table) {
                $table->dropColumn('loai_ngan_sach');
            });
        }

        if (!Schema::hasColumn('wallets', 'ngan_sach_goc')) {
            Schema::table('wallets', function (Blueprint $table) {
                $table->decimal('ngan_sach_goc', 15, 2)
                      ->default(0)
                      ->after('ten_ngan_sach')
                      ->comment('Ngân sách ban đầu đã đặt');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('wallets', 'ngan_sach_goc')) {
            Schema::table('wallets', function (Blueprint $table) {
                $table->dropColumn('ngan_sach_goc');
            });
        }

        if (!Schema::hasColumn('wallets', 'loai_ngan_sach')) {
            Schema::table('wallets', function (Blueprint $table) {
                $table->string('loai_ngan_sach')->nullable();
            });
        }
    }
};