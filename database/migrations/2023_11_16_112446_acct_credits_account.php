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
        if(!Schema::hasTable('acct_credits_account')) {
            Schema::create('acct_credits_account', function (Blueprint $table) {
                $table->id('credits_account_id');
                $table->unsignedBigInteger('credits_id')->nullable();
                $table->foreign('credits_id')->references('credits_id')->on('acct_credits')->onUpdate('cascade')->onDelete('set null');
                $table->unsignedBigInteger('member_id')->nullable();
                $table->unsignedBigInteger('branch_id')->nullable();
                $table->unsignedBigInteger('office_id')->nullable();
                $table->unsignedBigInteger('savings_account_id')->nullable();
                $table->unsignedBigInteger('source_fund_id')->nullable();
                $table->string('credits_account_no',50)->nullable();
                $table->string('credits_account_serial',50)->nullable();
                $table->integer('payment_type_id')->nullable();
                $table->integer('credits_payment_period')->nullable();
                $table->integer('credits_account_period')->nullable();
                $table->date('credits_account_date')->nullable();
                $table->date('credits_account_due_date')->nullable();
                $table->decimal('credits_account_amount',20)->nullable()->default(0);
                $table->decimal('credits_account_interest',10)->nullable()->default(0);
                $table->decimal('credits_account_interest_amount',20)->nullable()->default(0);
                $table->decimal('credits_account_adm_cost',20)->nullable()->default(0);
                $table->decimal('credits_account_provisi',20)->nullable()->default(0);
                $table->decimal('credits_account_komisi',20)->nullable()->default(0);
                $table->decimal('credits_account_notaris',20)->nullable()->default(0);
                $table->decimal('credits_account_amount_received',20)->nullable()->default(0);
                $table->decimal('credits_account_principal_amount',20)->nullable()->default(0);
                $table->decimal('credits_account_payment_amount',20)->nullable()->default(0);
                $table->decimal('credits_account_last_balance',20)->nullable()->default(0);
                $table->decimal('credits_account_interest_last_balance',20)->nullable()->default(0);
                $table->string('credits_account_payment_to')->nullable()->default(0);
                $table->date('credits_account_payment_date')->nullable();
                $table->date('credits_account_last_payment_date')->nullable();
                $table->decimal('credits_account_accumulated_fines',20)->nullable()->default(0);
                $table->text('credits_account_used')->nullable();
                $table->integer('credits_account_status')->nullable();
                $table->integer('credits_reschedule_status')->nullable();
                $table->integer('credits_account_last_number')->nullable();
                $table->integer('credits_approve_status')->nullable();
                $table->decimal('credits_account_temp_installment',20)->nullable();
                $table->uuid('auto_debet_credits_account_token')->nullable();

                $table->text('credits_account_agunan')->nullable();

                $table->uuid('credits_account_token')->nullable();
                $table->datetime('validation_at')->nullable();
                $table->unsignedBigInteger('validation_id')->nullable();
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
        Schema::drop('acct_credits_account');
    }
};
