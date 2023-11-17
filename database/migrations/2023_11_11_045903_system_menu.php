<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if(!Schema::hasTable('system_menu')) {
            Schema::create('system_menu', function (Blueprint $table) {
                $table->integer('id_menu');
                $table->primary('id_menu');
                $table->string('id',100)->nullable();
                $table->enum('type',['folder','file','function'])->nullable();
                $table->string('text',50)->nullable();
                $table->string('parent',50)->nullable();
                $table->string('image',50)->nullable();
                $table->string('menu_level',50)->nullable();
                $table->softDeletesTz();
            });
             // Insert admin user
            DB::table('system_menu')->insert([
               [ 'id_menu' => 1,'id' => 'index','type' => 'file','text' => 'Beranda','parent' => "#",'menu_level' => "1",],
               [ 'id_menu' => 2,'id' => 'client','type' => 'file','text' => 'Client','parent' => "#",'menu_level' => "1",],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('system_menu');
    }
};
