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
                $table->unsignedBigInteger('account_id')->nullable();
                $table->foreign('account_id')->references('account_id')->on('acct_account')->onUpdate('cascade')->onDelete('set null');
                $table->text('remark')->nullable();
                $table->string('type')->nullable();
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
