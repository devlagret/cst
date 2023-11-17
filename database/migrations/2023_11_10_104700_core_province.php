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
        if(!Schema::hasTable('core_province')) {
            Schema::create('core_province', function (Blueprint $table) {
                $table->id('province_id');
                $table->char('province_code',3)->nullable();
                $table->string('province_name')->nullable();
                $table->string('province_no',20)->nullable();
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
        Schema::drop('core_province');
    }
};
