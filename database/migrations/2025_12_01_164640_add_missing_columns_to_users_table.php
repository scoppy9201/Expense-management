<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Kiểm tra và thêm các cột còn thiếu
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone', 20)->nullable()->after('name');
            }
            
            if (!Schema::hasColumn('users', 'ngay_sinh')) {
                $table->date('ngay_sinh')->nullable()->after('phone');
            }
            
            if (!Schema::hasColumn('users', 'gioi_tinh')) {
                $table->enum('gioi_tinh', ['Nam', 'Nữ', 'Khác'])->nullable()->after('ngay_sinh');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'ngay_sinh', 'gioi_tinh']);
        });
    }
};
