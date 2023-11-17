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
        if(!Schema::hasTable('core_dusun')) {
            Schema::create('core_dusun', function (Blueprint $table) {
                $table->id('dusun_id');
                $table->unsignedBigInteger('kelurahan_id')->nullable();
                $table->foreign('kelurahan_id')->references('kelurahan_id')->on('core_kelurahan')->onUpdate('cascade')->onDelete('set null');
                $table->char('dusun_code',3)->nullable();
                $table->string('dusun_name')->nullable();
                $table->string('dusun_no',20)->nullable();
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
        Schema::drop('core_dusun');
    }
};
