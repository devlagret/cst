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
        if(!Schema::hasTable('core_product')) {
            Schema::create('core_product', function (Blueprint $table) {
                $table->id('product_id');
                $table->string('name')->nullable();
                $table->unsignedBigInteger('client_id')->nullable();
                $table->foreign('client_id')->references('client_id')->on('core_client')->onUpdate('cascade')->onDelete('set null');
                $table->text('remark')->nullable();
                $table->unsignedBigInteger('product_type_id')->nullable();
                $table->foreign('product_type_id')->references('product_type_id')->on('product_type')->onUpdate('cascade')->onDelete('set null');
                $table->string('payment_period')->nullable();
                $table->string('payment_period_to')->nullable();
                $table->timestamp('start_dev_date')->nullable();
                $table->timestamp('trial_date')->nullable();
                $table->timestamp('usage_date')->nullable();
                $table->timestamp('maintenance_date')->nullable();
                $table->integer('maintenance_count')->nullable();
                $table->integer('maintenance_price')->nullable();
                $table->integer('payment_type')->nullable();
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
        Schema::drop('core_product');
    }
};
