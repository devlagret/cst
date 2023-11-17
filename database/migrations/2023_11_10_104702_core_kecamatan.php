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
        if(!Schema::hasTable('core_kecamatan')) {
            Schema::create('core_kecamatan', function (Blueprint $table) {
                $table->id('kecamatan_id');
                $table->unsignedBigInteger('city_id')->nullable();
                $table->foreign('city_id')->references('city_id')->on('core_city')->onUpdate('cascade')->onDelete('set null');
                $table->char('city_code',3)->nullable();
                $table->char('kecamatan_code',3)->nullable();
                $table->string('kecamatan_name')->nullable();
                $table->string('kecamatan_no',20)->nullable();
                $table->string('city_no',20)->nullable();
                $table->timestamps();
                $table->softDeletesTz();
            });
            }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('core_kecamatan');
    }
};
