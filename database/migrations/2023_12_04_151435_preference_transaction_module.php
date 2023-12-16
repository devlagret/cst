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
        if(!Schema::hasTable('preference_transaction_module')) {
            Schema::create('preference_transaction_module', function (Blueprint $table) {
                $table->id('transaction_module_id');
                $table->string('transaction_module_name')->nullable();
                $table->string('transaction_module_code')->nullable();
                $table->integer('transaction_id')->nullable()->default(0);
                $table->string('transaction_controller')->nullable();
                $table->string('transaction_table')->nullable();
                $table->string('transaction_primary_key')->nullable();
                $table->string('status')->nullable()->default(0);
                $table->unsignedBigInteger('created_id')->nullable();
                $table->unsignedBigInteger('updated_id')->nullable();
                $table->unsignedBigInteger('deleted_id')->nullable();
                $table->timestamps();
                $table->softDeletesTz();
            });
            DB::table('preference_transaction_module')->insert([
                [ 'transaction_module_name' => 'Jurnal Umum', 'transaction_module_code' => 'JU', 'transaction_controller' => 'AcctJournalVoucher','transaction_table'=>'acct_journal_voucher','transaction_primary_key'=>'journal_voucher_id'],
                [ 'transaction_module_name' => 'Invoice', 'transaction_module_code' => 'SI', 'transaction_controller' => 'AcctInvoice','transaction_table'=>'acct_invoice','transaction_primary_key'=>'invoice_id'],
             ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('preference_transaction_module');
    }
};
