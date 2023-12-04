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
        if(!Schema::hasTable('acct_journal_voucher')) {
            Schema::create('acct_journal_voucher', function (Blueprint $table) {
                $table->id('journal_voucher_id');
                $table->unsignedBigInteger('client_id')->nullable();
                $table->foreign('client_id')->references('client_id')->on('core_client')->onUpdate('cascade')->onDelete('set null');
                $table->unsignedBigInteger('transaction_module_id')->nullable();
                $table->foreign('transaction_module_id')->references('transaction_module_id')->on('preference_transaction_module')->onUpdate('cascade')->onDelete('set null');
                $table->tinyInteger('journal_voucher_status')->nullable()->default(0);
                $table->string('transaction_module_code')->nullable();
                $table->unsignedBigInteger('transaction_journal_id')->nullable();
                $table->string('transaction_journal_no')->nullable();
                $table->string('journal_voucher_no')->nullable();
                $table->string('journal_voucher_period')->nullable();
                $table->date('journal_voucher_date')->nullable();
                $table->string('journal_voucher_title')->nullable();
                $table->string('journal_voucher_description')->nullable();
                $table->string('journal_voucher_token')->nullable();
                $table->boolean('reverse_state')->nullable()->default(0);
                $table->boolean('pickup_status')->nullable()->default(0);
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
        Schema::drop('acct_journal_voucher');
    }
};
