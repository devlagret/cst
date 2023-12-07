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
        if(!Schema::hasTable('acct_account_setting_group')) {
            Schema::create('acct_account_setting_group', function (Blueprint $table) {
                $table->id('account_setting_group_id');
                $table->string('name')->nullable();
                $table->unsignedBigInteger('created_id')->nullable();
                $table->unsignedBigInteger('updated_id')->nullable();
                $table->unsignedBigInteger('deleted_id')->nullable();
                $table->timestamps();
                $table->softDeletesTz();
            });
            DB::table('acct_account_setting_group')->insert([
                [ 'name' => "Penjualan Software"],
                [ 'name' => "Penjualan Multimedia"],
                [ 'name' => "Penjualan Hardware"],
             ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('acct_account_setting_group');
    }
};
