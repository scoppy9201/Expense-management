<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('ten_danh_muc')->after('id');
            $table->enum('loai_danh_muc', ['THU','CHI'])->after('ten_danh_muc');
            $table->unsignedBigInteger('danh_muc_cha_id')->nullable()->after('loai_danh_muc');
            $table->string('bieu_tuong', 50)->nullable()->after('danh_muc_cha_id');
            $table->text('mo_ta')->nullable()->after('bieu_tuong');
        });
    }

    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn([
                'ten_danh_muc',
                'loai_danh_muc',
                'danh_muc_cha_id',
                'bieu_tuong',
                'mo_ta',
            ]);
        });
    }
};
