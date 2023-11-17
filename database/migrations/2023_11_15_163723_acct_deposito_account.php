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
        if(!Schema::hasTable('acct_deposito_account')) {
            Schema::create('acct_deposito_account', function (Blueprint $table) {
                $table->id('deposito_account_id');
                $table->unsignedBigInteger('deposito_id')->nullable();
                $table->foreign('deposito_id')->references('deposito_id')->on('acct_deposito')->onUpdate('cascade')->onDelete('set null');
                $table->unsignedBigInteger('member_id')->nullable();
                $table->unsignedBigInteger('branch_id')->nullable();
                $table->unsignedBigInteger('office_id')->nullable();
                $table->unsignedBigInteger('savings_account_id')->nullable();
                $table->string('deposito_account_no',50)->nullable();
                $table->string('deposito_account_serial_no',50)->nullable();
                $table->integer('deposito_account_period')->nullable();
                $table->date('deposito_account_date')->nullable();
                $table->date('deposito_account_due_date')->nullable();
                $table->decimal('deposito_account_amount',20)->nullable();
                $table->decimal('deposito_account_nisibah',20)->nullable();
                $table->decimal('deposito_account_interest',10)->nullable();
                $table->decimal('deposito_account_interest_amount',20)->nullable();
                $table->date('deposito_account_closed_date')->nullable();
                $table->decimal('deposito_account_penalty',20)->nullable();
                $table->boolean('deposito_account_status')->nullable();
                $table->boolean('deposito_account_extra_type')->nullable();
                $table->boolean('deposito_account_blockir_type')->nullable();
                $table->boolean('deposito_account_blockir_status')->nullable();
                $table->decimal('deposito_account_blockir_amount',20)->nullable();
                $table->string('deposito_member_heir')->nullable();
                $table->text('deposito_member_heir_address')->nullable();
                $table->boolean('deposito_member_heir_relationship')->nullable();
                $table->date('deposito_process_last_date')->nullable();
                $table->uuid('deposito_account_token')->nullable();
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
        Schema::drop('acct_deposito_account');
    }
};
