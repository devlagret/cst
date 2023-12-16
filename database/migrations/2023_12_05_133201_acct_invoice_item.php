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
        if(!Schema::hasTable('acct_invoice_item')) {
            Schema::create('acct_invoice_item', function (Blueprint $table) {
                $table->id('invoice_item_id');
                $table->date('payment_date')->nullable();
                $table->unsignedBigInteger('item_id')->nullable();
                $table->unsignedBigInteger('invoice_id')->nullable();
                $table->foreign('invoice_id')->references('invoice_id')->on('acct_invoice')->onUpdate('cascade')->onDelete('set null');
                $table->unsignedBigInteger('account_id')->nullable();
                $table->foreign('account_id')->references('account_id')->on('acct_account')->onUpdate('cascade')->onDelete('set null');
                $table->text('remark')->nullable();
                $table->decimal('subtotal_amount',20)->nullable()->default(0);
                $table->decimal('total_amount',20)->nullable()->default(0);
                $table->tinyInteger('discount_percentage')->nullable()->default(0);
                $table->decimal('discount_amount',20)->nullable()->default(0);
                $table->tinyInteger('payment_type')->nullable();
                $table->tinyInteger('payment_status')->nullable()->default(0);
                $table->tinyInteger('invoice_type')->nullable()->default(0)->comment('for :: 0:general,1:addon,2:termin');
                $table->tinyInteger('invoice_status')->nullable()->default(0);
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
        Schema::drop('acct_invoice_item');
    }
};
