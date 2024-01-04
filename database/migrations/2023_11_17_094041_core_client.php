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
        if(!Schema::hasTable('core_client')) {
            Schema::create('core_client', function (Blueprint $table) {
                $table->id('client_id');
                $table->string('name')->nullable();
                $table->text('address')->nullable();
                $table->string('contact_person')->nullable();
                $table->string('phone')->nullable();
                $table->string('email')->nullable();
                $table->uuid('client_token')->nullable();
                $table->unsignedBigInteger('created_id')->nullable();
                $table->unsignedBigInteger('updated_id')->nullable();
                $table->unsignedBigInteger('deleted_id')->nullable();
                $table->timestamps();
                $table->softDeletesTz();
            });
            DB::table('core_client')->insert([
                [ 'name' => 'BAZNAS Kab. Sragen',  'address' => ' Jl. Raya Timur KM. 4 Pilangsari Ngrampal Sragen',              'contact_person' => 'Ibu Dewi',],
                [ 'name' => 'KOPERASI MAKMUR SEJAHTERA INDONESIA',  'address' => 'Gd Spazio Tower Lt.2 No. 212 JL. MayJend Yono Soewoyo 3, Surabaya JAWA TIMUR ',             'contact_person' => 'Bp. RUSEEL / Ibu SHINTA',],
                [ 'name' => 'KOPERASI SUMBER DANA MAKMUR',  'address' => 'JL. Ir H JUANDA 171 Kel. Tonatan Kec. Ponorogo Kab. Ponorogo',            'contact_person' => 'Ibu Hariyani',],
             ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('core_client');
    }
};