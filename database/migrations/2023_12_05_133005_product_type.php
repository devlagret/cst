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
        if(!Schema::hasTable('product_type')) {
            Schema::create('product_type', function (Blueprint $table) {
                $table->id('product_type_id');
                $table->string('name')->nullable();
                $table->string('code')->nullable();
                $table->unsignedBigInteger('account_setting_group_id')->nullable();
                $table->foreign('account_setting_group_id')->references('account_setting_group_id')->on('acct_account_setting_group')->onUpdate('cascade')->onDelete('set null');
                $table->unsignedBigInteger('created_id')->nullable();
                $table->unsignedBigInteger('updated_id')->nullable();
                $table->unsignedBigInteger('deleted_id')->nullable();
                $table->timestamps();
                $table->softDeletesTz();
            });
            DB::table('product_type')->insert([
                [ 'name' => 'Divisi Software','code' => 'SD','account_setting_group_id' => 1],
                [ 'name' => 'Divisi Multimedia','code' => 'MD','account_setting_group_id' =>1],
                [ 'name' => 'Penjualan Hardware','code' => 'PD','account_setting_group_id' => 1],
                [ 'name' => 'Dagang Device','code' => 'DD','account_setting_group_id' => 1],
             ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('product_type');

    }
};
