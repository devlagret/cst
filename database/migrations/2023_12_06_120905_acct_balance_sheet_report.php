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
        if(!Schema::hasTable('acct_balance_sheet_report')) {
            Schema::create('acct_balance_sheet_report', function (Blueprint $table) {
                $table->id('balance_sheet_report_id');
                $table->integer('report_no')->nullable();
                $table->unsignedBigInteger('account_id1')->nullable();
                $table->foreign('account_id1')->references('account_id')->on('acct_account')->onUpdate('cascade')->onDelete('set null');
                $table->string('account_code1')->nullable();
                $table->string('account_name1')->nullable();
                $table->unsignedBigInteger('account_id2')->nullable();
                $table->foreign('account_id2')->references('account_id')->on('acct_account')->onUpdate('cascade')->onDelete('set null');
                $table->string('account_code2')->nullable();
                $table->string('account_name2')->nullable();

                $table->string('report_formula1')->nullable();
                $table->string('report_operator1')->nullable();
                $table->integer('report_type1')->nullable();
                $table->integer('report_tab1')->nullable();
                $table->integer('report_bold1')->nullable();
                $table->string('report_formula2')->nullable();
                $table->string('report_operator2')->nullable();
                $table->integer('report_type2')->nullable();
                $table->integer('report_tab2')->nullable();
                $table->integer('report_bold2')->nullable();

                $table->integer('balance_report_type')->nullable();
                $table->integer('balance_report_type1')->nullable();

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
        Schema::drop('acct_balance_sheet_report');
    }
};
