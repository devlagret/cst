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
        Schema::create('acct_bank_accounts', function (Blueprint $table) {
            $table->id('bank_account_id');
            $table->string('bank_account_code');
            $table->string('bank_account_name');
            $table->string('bank_account_no');
            $table->unsignedBigInteger('account_id')->nullable();
            $table->foreign('account_id')->references('account_id')->on('acct_account');
            $table->unsignedBigInteger('created_id')->nullable();
            $table->unsignedBigInteger('updated_id')->nullable();
            $table->unsignedBigInteger('deleted_id')->nullable();
            $table->softDeletesTz();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acct_bank_accounts');
    }
};