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
        if(!Schema::hasTable('core_product_addon')) {
            Schema::create('core_product_addon', function (Blueprint $table) {
                $table->id('product_addon_id');
                $table->unsignedBigInteger('product_id')->nullable();
                $table->foreign('product_id')->references('product_id')->on('core_product')->onUpdate('cascade')->onDelete('set null');
                $table->string('name')->nullable();
                $table->date('date')->nullable();
                $table->text('remark')->nullable();
                $table->decimal('amount',20)->default(0);
                $table->tinyInteger('payment_status')->nullable()->default(0);
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
        Schema::drop('core_product_addon');
    }
};
