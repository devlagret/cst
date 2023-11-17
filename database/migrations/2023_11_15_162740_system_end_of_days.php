<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if(!Schema::hasTable('system_end_of_days')) {
            Schema::create('system_end_of_days', function (Blueprint $table) {
                $table->id('end_of_days_id');
                $table->integer('end_of_days_status')->nullable();
                $table->decimal('debit_amount',20)->nullable();
                $table->decimal('credit_amount',20)->nullable();
                $table->integer('close_id')->nullable();
                $table->integer('open_id')->nullable();
                $table->dateTime('open_at')->nullable();
                $table->dateTime('closed_at')->nullable();
                $table->unsignedBigInteger('created_id')->nullable();
                $table->unsignedBigInteger('updated_id')->nullable();
                $table->unsignedBigInteger('deleted_id')->nullable();
                $table->timestamps();
                $table->softDeletesTz();
            });
             // Insert admin user
            DB::table('system_end_of_days')->insert(array(
                'end_of_days_status' => 1,
                'debit_amount' => 0,
                'credit_amount' => 0,
                'open_at' => date('Y-m-d H:i:s')
            ));
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('system_end_of_days');
    }
};
