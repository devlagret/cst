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
        //
        if(!Schema::hasTable('asset_menu')) {
            Schema::create('asset_menu', function (Blueprint $table) {
                $table->id('asset_id');
                $table->string('name')->nullable();
                $table->date('buy_date')->nullable();
                $table->integer('price')->nullable();
                $table->integer('acquisition_amount')->nullable();
                $table->integer('estimated_age')->nullable();
                $table->integer('residual_amount')->nullable();
                $table->text('remark')->nullable();
                $table->unsignedBigInteger('created_id')->nullable();
                $table->unsignedBigInteger('updated_id')->nullable();
                $table->unsignedBigInteger('deleted_id')->nullable();
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
        //
    }
};
