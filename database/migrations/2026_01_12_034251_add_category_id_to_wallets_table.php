<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('wallets', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->nullable()->after('user_id');
        });

        $wallets = DB::table('wallets')->whereNull('category_id')->get();
        foreach ($wallets as $wallet) {
            $firstCategory = DB::table('categories')
                              ->where('user_id', $wallet->user_id)
                              ->first();
            
            if ($firstCategory) {
                DB::table('wallets')
                  ->where('id', $wallet->id)
                  ->update(['category_id' => $firstCategory->id]);
            }
        }

        Schema::table('wallets', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->nullable(false)->change();
            
            $table->foreign('category_id')
                  ->references('id')->on('categories')
                  ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wallets', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
    }
};