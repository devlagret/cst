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
        if(!Schema::hasTable('core_city')) {
            Schema::create('core_city', function (Blueprint $table) {
                $table->id('city_id');
                $table->unsignedBigInteger('province_id')->nullable();
                $table->foreign('province_id')->references('province_id')->on('core_province')->onUpdate('cascade')->onDelete('set null');
                $table->char('province_code',3)->nullable();
                $table->char('city_code',3)->nullable();
                $table->string('city_name')->nullable();
                $table->string('province_no',20)->nullable();
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
        Schema::drop('core_city');
    }
};
