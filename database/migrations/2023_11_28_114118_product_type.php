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
                $table->unsignedBigInteger('account_id')->nullable();
                $table->foreign('account_id')->references('account_id')->on('acct_account')->onUpdate('cascade')->onDelete('set null');
                $table->unsignedBigInteger('created_id')->nullable();
                $table->unsignedBigInteger('updated_id')->nullable();
                $table->unsignedBigInteger('deleted_id')->nullable();
                $table->timestamps();
                $table->softDeletesTz();
            });
            DB::table('product_type')->insert([
                [ 'name' => 'Software','code' => 'SD','account_id' => 1],
                [ 'name' => 'Grafik','code' => 'GD','account_id' =>1],
                [ 'name' => 'Penjualan Alat','code' => 'PD','account_id' => 1],
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
