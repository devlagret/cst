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
        if(!Schema::hasTable('acct_invoice')) {
            Schema::create('acct_invoice', function (Blueprint $table) {
                $table->id('invoice_id');
                $table->date('invoice_date')->nullable();
                $table->unsignedBigInteger('client_id')->nullable();
                $table->foreign('client_id')->references('client_id')->on('core_client')->onUpdate('cascade')->onDelete('set null');
                $table->unsignedBigInteger('product_id')->nullable();
                $table->foreign('product_id')->references('product_id')->on('core_product')->onUpdate('cascade')->onDelete('set null');
                $table->string('invoice_no')->nullable();
                $table->string('remark')->nullable();
                $table->decimal('subtotal_amount',20)->nullable()->default(0);
                $table->decimal('adjustment_amount',20)->nullable()->default(0);
                $table->decimal('total_amount',20)->nullable()->default(0);
                $table->decimal('payed_amount',20)->nullable()->default(0);
                $table->decimal('receivables_amount',20)->nullable()->default(0);
                $table->tinyInteger('discount_percentage')->nullable()->default(0);
                $table->decimal('discount_amount',20)->nullable()->default(0);
                $table->tinyInteger('tax_ppn_percentage')->nullable()->default(0);
                $table->decimal('tax_ppn_amount',20)->nullable()->default(0);
                $table->tinyInteger('payment_type')->nullable()->default(0)->comment('0:cash,1:transfer');
                $table->tinyInteger('invoice_type')->nullable()->default(0)->comment('0:multi,3:maintenance');
                $table->tinyInteger('invoice_status')->nullable()->default(0)->comment("0:hasn't payed, 1:payed");
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
        Schema::drop('acct_invoice');
    }
};