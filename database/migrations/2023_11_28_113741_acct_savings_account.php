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
        if(!Schema::hasTable('acct_savings_account')) {
            Schema::create('acct_savings_account', function (Blueprint $table) {
                $table->id('savings_account_id');
                $table->unsignedBigInteger('branch_id')->nullable();
                $table->foreign('branch_id')->references('branch_id')->on('core_branch')->onUpdate('cascade')->onDelete('set null');
                $table->unsignedBigInteger('savings_id')->nullable();
                $table->foreign('savings_id')->references('savings_id')->on('acct_savings')->onUpdate('cascade')->onDelete('set null');
                $table->unsignedBigInteger('member_id')->nullable();
                $table->foreign('member_id')->references('member_id')->on('core_member')->onUpdate('cascade')->onDelete('set null');
                $table->unsignedBigInteger('office_id')->nullable();
                $table->foreign('office_id')->references('office_id')->on('core_office')->onUpdate('cascade')->onDelete('set null');
                $table->string('savings_account_no')->nullable();
                $table->integer('saving_account_period')->nullable();
                $table->date('savings_account_date')->nullable();
                $table->date('savings_account_pickup_date')->nullable();
                $table->boolean('unblock_state')->nullable();
                $table->unsignedBigInteger('unblock_id')->nullable();
                $table->timestamp('unblock_at')->nullable();
                $table->decimal('savings_account_first_deposit_amount',20)->default(0);
                $table->decimal('savings_account_opening_balance',20)->default(0);
                $table->decimal('savings_account_last_balance',20)->default(0);
                $table->decimal('savings_account_last_balance_backup',20)->default(0);
                $table->decimal('savings_account_daily_average_balance',20)->default(0);
                $table->decimal('savings_account_adm_amount',20)->default(0);
                $table->decimal('savings_account_last_balance_phu',20)->default(0);
                $table->string('savings_member_heir')->nullable();
                $table->text('savings_member_heir_address')->nullable();
                $table->tinyInteger('savings_member_heir_relationship')->nullable();
                $table->tinyInteger('savings_account_blockir_type')->nullable();
                $table->boolean('savings_account_blockir_status')->nullable();
                $table->decimal('savings_account_blockir_amount',20)->default(0);
                $table->integer('savings_account_last_number')->default(0);
                $table->uuid('savings_account_token')->nullable();
                $table->boolean('savings_account_status')->default(0)->comment("0 : Open 1 : Closed");
                $table->string('operated_name')->nullable();
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
        Schema::drop('acct_savings_account');
    }
};
