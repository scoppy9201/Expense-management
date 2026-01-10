<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            // Thêm cột trạng thái
            $table->boolean('trang_thai')->default(true)->after('mo_ta');
            // Update cột bieu_tuong
            $table->string('bieu_tuong', 100)->default('money.png')->change();
        });
    }

    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('trang_thai');
        });
    }
};
