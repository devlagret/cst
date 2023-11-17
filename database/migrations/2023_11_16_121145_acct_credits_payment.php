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
        if(!Schema::hasTable('acct_credits_payment')) {
            Schema::create('acct_credits_payment', function (Blueprint $table) {
                $table->id('credits_payment_id');
                $table->unsignedBigInteger('credits_account_id')->nullable();
                $table->foreign('credits_account_id')->references('credits_account_id')->on('acct_credits_account')->onUpdate('cascade')->onDelete('set null');
                $table->unsignedBigInteger('credits_id')->nullable();
                $table->foreign('credits_id')->references('credits_id')->on('acct_credits')->onUpdate('cascade')->onDelete('set null');
                $table->unsignedBigInteger('member_id')->nullable();
                $table->unsignedBigInteger('branch_id')->nullable();
                $table->unsignedBigInteger('bank_account_id')->nullable();
                $table->unsignedBigInteger('savings_account_id')->nullable();
                $table->unsignedBigInteger('credits_payment_branch')->nullable();
                $table->date('credits_payment_date')->nullable();
                $table->date('credits_account_payment_date')->nullable();
                $table->decimal('credits_payment_amount',20)->nullable()->default(0);
                $table->decimal('credits_payment_principal',20)->nullable()->default(0);
                $table->decimal('credits_payment_interest',20)->nullable()->default(0);
                $table->decimal('credits_interest_income',20)->nullable()->default(0);
                $table->decimal('credits_others_income',20)->nullable()->default(0);
                $table->decimal('credits_principal_opening_balance',20)->nullable()->default(0);
                $table->decimal('credits_principal_last_balance',20)->nullable()->default(0);
                $table->decimal('credits_interest_opening_balance',20)->nullable()->default(0);
                $table->decimal('credits_interest_last_balance',20)->nullable()->default(0);
                $table->decimal('credits_payment_day_of_delay',20)->nullable()->default(0);
                $table->string('credits_payment_to',20)->nullable()->default(0);
                $table->decimal('credits_payment_fine',20)->nullable()->default(0);
                $table->decimal('credits_payment_fine_last_balance',20)->nullable()->default(0);
                $table->integer('credits_branch_status')->nullable()->default(0);
                $table->integer('credits_payment_status')->nullable()->default(0);
                $table->integer('credits_payment_type')->nullable()->default(0);
                $table->integer('credits_print_status')->nullable()->default(0);
                $table->string('operated_name')->nullable();
                $table->uuid('credits_payment_token')->nullable();
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
        Schema::drop('acct_credits_payment');
    }
};
