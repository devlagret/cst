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
        if(!Schema::hasTable('acct_journal_voucher_item')) {
            Schema::create('acct_journal_voucher_item', function (Blueprint $table) {
                $table->id('journal_voucher_item_id');
                $table->unsignedBigInteger('journal_voucher_id')->nullable();
                $table->foreign('journal_voucher_id')->references('journal_voucher_id')->on('acct_journal_voucher')->onUpdate('cascade')->onDelete('set null');
                $table->unsignedBigInteger('account_id')->nullable();
                $table->foreign('account_id')->references('account_id')->on('acct_account')->onUpdate('cascade')->onDelete('set null');
                $table->string('journal_voucher_description')->nullable();
                $table->decimal('journal_voucher_amount',20)->nullable()->default(0);
                $table->boolean('account_id_status')->nullable()->default(0);
                $table->boolean('account_id_default_status')->nullable()->default(0);
                $table->decimal('journal_voucher_debit_amount',20)->nullable()->default(0);
                $table->decimal('journal_voucher_credit_amount',20)->nullable()->default(0);
                $table->boolean('reverse_state')->nullable()->default(0);
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
        Schema::drop('acct_journal_voucher_item');
    }
};
