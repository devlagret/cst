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
        if(!Schema::hasTable('acct_credits')) {
            Schema::create('acct_credits', function (Blueprint $table) {
                $table->id('credits_id');
                $table->string('credits_name',50)->nullable();
                $table->integer('credits_number')->nullable();
                $table->decimal('credits_fine',10)->nullable();
                $table->string('credits_code',20)->nullable();
                $table->unsignedBigInteger('receivable_account_id')->nullable();
                $table->foreign('receivable_account_id')->references('account_id')->on('acct_account')->onUpdate('cascade')->onDelete('set null');
                $table->unsignedBigInteger('income_account_id')->nullable();
                $table->foreign('income_account_id')->references('account_id')->on('acct_account')->onUpdate('cascade')->onDelete('set null');
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
        Schema::drop('acct_credits');
    }
};
