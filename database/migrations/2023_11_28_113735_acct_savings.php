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
        if(!Schema::hasTable('acct_savings')) {
            Schema::create('acct_savings', function (Blueprint $table) {
                $table->id('savings_id');
                $table->unsignedBigInteger('account_id')->nullable();
                $table->unsignedBigInteger('account_basil_id')->nullable();
                $table->foreign('account_id')->references('account_id')->on('acct_account')->onUpdate('cascade')->onDelete('set null');
                $table->string('savings_code')->nullable();
                $table->string('savings_name')->nullable();
                $table->decimal('minimum_first_deposit_amount',20)->default(0);
                $table->integer('savings_number')->nullable();
                $table->decimal('savings_last_balance',20)->default(0);
                $table->decimal('savings_profit_sharing',10)->default(0);
                $table->decimal('savings_interest_rate',3)->default(0);
                $table->decimal('savings_basil',10)->default(0);
                $table->boolean('savings_status')->nullable();
                $table->unsignedBigInteger('created_id')->nullable();
                $table->unsignedBigInteger('updated_id')->nullable();
                $table->unsignedBigInteger('deleted_id')->nullable();
                $table->timestamps();
                $table->softDeletesTz();
            });
        }
    }
    public function down(): void
    {
        Schema::drop('acct_savings');
    }
};
