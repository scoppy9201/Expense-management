<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wallets', function (Blueprint $table) {
            // Thêm các cột mới với tên ngân sách
            if (!Schema::hasColumn('wallets', 'ten_ngan_sach')) {
                $table->string('ten_ngan_sach')->after('user_id');
            }
            
            if (!Schema::hasColumn('wallets', 'so_du')) {
                $table->decimal('so_du', 15, 2)->default(0)->after('ten_ngan_sach');
            }
            
            if (!Schema::hasColumn('wallets', 'loai_ngan_sach')) {
                $table->string('loai_ngan_sach')->nullable()->after('so_du');
            }
            
            if (!Schema::hasColumn('wallets', 'mo_ta')) {
                $table->text('mo_ta')->nullable()->after('loai_ngan_sach');
            }
            
            if (!Schema::hasColumn('wallets', 'trang_thai')) {
                $table->boolean('trang_thai')->default(true);
            }
        });
    }

    public function down(): void
    {
        Schema::table('wallets', function (Blueprint $table) {
            $table->dropColumn(['ten_ngan_sach', 'so_du', 'loai_ngan_sach', 'mo_ta', 'trang_thai']);
        });
    }
};