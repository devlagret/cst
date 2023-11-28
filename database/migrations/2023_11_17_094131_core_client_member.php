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
        if(!Schema::hasTable('core_client_member')) {
            Schema::create('core_client_member', function (Blueprint $table) {
                $table->id('client_member_id');
                $table->unsignedBigInteger('client_id');
                $table->foreign('client_id')->references('client_id')->on('core_client')->onUpdate('cascade')->onDelete('cascade');
                $table->string('name')->nullable();
                $table->string('position')->nullable()->comment('PIC');
                $table->string('phone')->nullable();
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
        Schema::drop('core_client_member');
    }
};
