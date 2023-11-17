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
        if(!Schema::hasTable('preference_collectibility')) {
            Schema::create('preference_collectibility', function (Blueprint $table) {
                $table->id('collectibility_id');
                $table->string('collectibility_name',50)->nullable();
                $table->integer('collectibility_bottom')->nullable()->default(0);
                $table->integer('collectibility_top')->nullable()->default(0);
                $table->decimal('collectibility_ppap',10)->nullable();
                $table->unsignedBigInteger('created_id')->nullable();
                $table->unsignedBigInteger('updated_id')->nullable();
                $table->unsignedBigInteger('deleted_id')->nullable();
                $table->timestamps();
                $table->softDeletesTz();
            });
            // Insert data
            DB::table('preference_collectibility')->insert([
                ['collectibility_name' => "LANCAR",'collectibility_bottom' => 0,'collectibility_top' => 30,'collectibility_ppap' => 1.50],
                ['collectibility_name' => "KURANG LANCAR",'collectibility_bottom' => 31,'collectibility_top' => 60,'collectibility_ppap' => 2.50],
                ['collectibility_name' => "DIRAGUKAN",'collectibility_bottom' => 61,'collectibility_top' => 90,'collectibility_ppap' => 3.50],
                ['collectibility_name' => "MACET",'collectibility_bottom' => 91,'collectibility_top' => 2147483647,'collectibility_ppap' => 4.50],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('preference_collectibility');
    }
};
