<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 20)->nullable()->after('name');
            $table->date('ngay_sinh')->nullable()->after('phone');
            $table->enum('gioi_tinh', ['Nam', 'Nữ', 'Khác'])->nullable()->after('ngay_sinh');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'ngay_sinh', 'gioi_tinh']);
        });
    }
};