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
        if(!Schema::hasTable('acct_deposito')) {
            Schema::create('acct_deposito', function (Blueprint $table) {
                $table->id('deposito_id');
                $table->unsignedBigInteger('account_id')->nullable();
                $table->foreign('account_id')->references('account_id')->on('acct_account')->onUpdate('cascade')->onDelete('set null');
                $table->unsignedBigInteger('account_basil_id')->nullable();
                $table->foreign('account_basil_id')->references('account_id')->on('acct_account')->onUpdate('cascade')->onDelete('set null');
                $table->string('deposito_code',20)->nullable();
                $table->string('deposito_name',50)->nullable();
                $table->integer('deposito_number')->nullable();
                $table->integer('deposito_period')->nullable();
                $table->decimal('deposito_interest_rate',10)->nullable();
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
        Schema::drop('acct_deposito');
    }
};
