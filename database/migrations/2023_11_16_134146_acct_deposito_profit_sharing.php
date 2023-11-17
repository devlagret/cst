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
        if(!Schema::hasTable('acct_deposito_profit_sharing')) {
            Schema::create('acct_deposito_profit_sharing', function (Blueprint $table) {
                $table->id('deposito_profit_sharing_id');
                $table->unsignedBigInteger('deposito_account_id')->nullable();
                $table->foreign('deposito_account_id')->references('deposito_account_id')->on('acct_deposito_account')->onUpdate('cascade')->onDelete('set null');
                $table->unsignedBigInteger('member_id')->nullable();
                $table->unsignedBigInteger('branch_id')->nullable();
                $table->unsignedBigInteger('savings_account_id')->nullable();
                $table->unsignedBigInteger('deposito_id')->nullable();
                $table->foreign('deposito_id')->references('deposito_id')->on('acct_deposito')->onUpdate('cascade')->onDelete('set null');
                $table->decimal('deposito_account_nisbah',20)->nullable()->default(0);
                $table->decimal('deposito_account_interest',10)->nullable()->default(0);
                $table->date('deposito_profit_sharing_due_date')->nullable();
                $table->date('deposito_profit_sharing_date')->nullable();
                $table->decimal('deposito_index_amount',10,5)->nullable()->default(0);
                $table->decimal('deposito_daily_average_balance',20)->nullable()->default(0);
                $table->decimal('deposito_profit_sharing_amount',20)->nullable()->default(0);
                $table->decimal('deposito_account_last_balance',20)->nullable()->default(0);
                $table->integer('deposito_profit_sharing_period')->nullable()->default(0);
                $table->boolean('deposito_profit_sharing_status')->nullable()->default(0);
                $table->uuid('deposito_profit_sharing_token')->nullable();
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
        Schema::drop('acct_deposito_profit_sharing');

    }
};
