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
        if(!Schema::hasTable('company_setting')) {
            Schema::create('company_setting', function (Blueprint $table) {
                $table->id('setting_id');
                $table->string('name')->nullable();
                $table->string('value')->nullable();
                $table->unsignedBigInteger('company_id')->nullable();
                $table->foreign('company_id')->references('company_id')->on('preference_company')->onUpdate('cascade')->onDelete('set null');
                $table->unsignedBigInteger('account_id')->nullable();
                $table->foreign('account_id')->references('account_id')->on('acct_account')->onUpdate('cascade')->onDelete('set null');
                $table->tinyInteger('account_setting_status')->nullable()->default(0);
                $table->unsignedBigInteger('created_id')->nullable();
                $table->unsignedBigInteger('updated_id')->nullable();
                $table->unsignedBigInteger('deleted_id')->nullable();
                $table->timestamps();
                $table->softDeletesTz();
            });
            DB::table('company_setting')->insert([
                [ 'account_id' => 8,  'name' => 'receivables_account','company_id'=>1],
                [ 'account_id' => 3,  'name' => 'cash_account','company_id'=>1],
                [ 'account_id' => 36,  'name' => 'maintenance_account','company_id'=>1],
             ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('company_setting');
    }
};
