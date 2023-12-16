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
        if(!Schema::hasTable('acct_account_setting')) {
            Schema::create('acct_account_setting', function (Blueprint $table) {
                $table->id('account_setting_id');
                $table->unsignedBigInteger('account_id')->nullable();
                $table->foreign('account_id')->references('account_id')->on('acct_account')->onUpdate('cascade')->onDelete('set null');
                $table->unsignedBigInteger('branch_id')->nullable();
                $table->foreign('branch_id')->references('branch_id')->on('core_branch')->onUpdate('cascade')->onDelete('set null');
                $table->string('account_setting_name')->nullable();
                $table->tinyInteger('account_setting_status')->nullable()->default(0);
                $table->tinyInteger('account_default_status')->nullable()->default(0);
                $table->unsignedBigInteger('created_id')->nullable();
                $table->unsignedBigInteger('updated_id')->nullable();
                $table->unsignedBigInteger('deleted_id')->nullable();
                $table->timestamps();
                $table->softDeletesTz();
            });
            DB::table('acct_account_setting')->insert([
                [ 'account_id' => 1,  'account_setting_name' => 'application_receivables', 'account_setting_status' => 0],
                [ 'account_id' => 2,  'account_setting_name' => 'application_cash_receivables', 'account_setting_status' => 1],
             ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('acct_account_setting');
    }
};
