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
        if(!Schema::hasTable('core_kelurahan')) {
            Schema::create('core_kelurahan', function (Blueprint $table) {
                $table->id('kelurahan_id');
                $table->unsignedBigInteger('kecamatan_id')->nullable();
                $table->foreign('kecamatan_id')->references('kecamatan_id')->on('core_kecamatan')->onUpdate('cascade')->onDelete('set null');
                $table->char('kelurahan_code',3)->nullable();
                $table->char('kecamatan_code',3)->nullable();
                $table->string('kelurahan_name')->nullable();
                $table->string('kecamatan_no',20)->nullable();
                $table->string('kelurahan_no',20)->nullable();
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
        Schema::drop('core_kelurahan');
    }
};
