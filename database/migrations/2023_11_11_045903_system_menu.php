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
                $table->string('id_menu',10);
                $table->primary('id_menu');
                $table->string('id',100)->nullable();
                $table->enum('type',['folder','file','function'])->nullable();
                $table->string('text',50)->nullable();
                $table->string('parent',50)->nullable();
                $table->string('image',50)->nullable();
                $table->string('menu_level',50)->nullable();
                $table->softDeletesTz();
            });
            DB::table('system_menu')->insert([
               [ 'id_menu' => 1,  'id' => 'index',              'type' => 'file','text' => 'Beranda','parent' => "#",'menu_level' => "1",],
               [ 'id_menu' => 2,  'id' => 'client',             'type' => 'file','text' => 'Client','parent' => "#",'menu_level' => "1",],
               [ 'id_menu' => 3,  'id' => 'product',            'type' => 'file','text' => 'Produk','parent' => "#",'menu_level' => "1",],
               [ 'id_menu' => 4,  'id' => 'invoice',            'type' => 'file','text' => 'Invoice','parent' => "#",'menu_level' => "1",],
               [ 'id_menu' => 5,  'id' => '#',                  'type' => 'folder','text' => 'Akutansi','parent' => "#",'menu_level' => "1",],
               [ 'id_menu' => 51, 'id' => 'account',            'type' => 'file','text' => 'No. Perkiraan','parent' => "5",'menu_level' => "2",],
               [ 'id_menu' => 52, 'id' => 'journal-voucher',    'type' => 'file','text' => 'Jurnal Umum','parent' => "5",'menu_level' => "2",],
               [ 'id_menu' => 53, 'id' => 'journal-memorial',   'type' => 'file','text' => 'Jurnal Memorial','parent' => "5",'menu_level' => "2",],
               [ 'id_menu' => 54, 'id' => 'balance-sheet',      'type' => 'file','text' => 'Laporan Neraca','parent' => "5",'menu_level' => "2",],
               [ 'id_menu' => 6,  'id' => '#',                  'type' => 'folder','text' => 'Preferensi','parent' => "#",'menu_level' => "1",],
               [ 'id_menu' => 61, 'id' => 'preference-company', 'type' => 'file','text' => 'Perusahaan','parent' => "6",'menu_level' => "2",],
               [ 'id_menu' => 62, 'id' => 'product-type',       'type' => 'file','text' => 'Tipe Produk','parent' => "6",'menu_level' => "2",],
               [ 'id_menu' => 63, 'id' => 'acct-account-setting','type' => 'file','text' => 'Pengaturan Akun','parent' => "6",'menu_level' => "2",],
               [ 'id_menu' => 64, 'id' => 'asset',               'type' => 'file','text' => 'Asset','parent' => "6",'menu_level' => "2",],               
               [ 'id_menu' => 65, 'id' => 'bank-account',        'type' => 'file','text' => 'Account Bank','parent' => "6",'menu_level' => "2",],               
               [ 'id_menu' => 7, 'id' => '#',                     'type' => 'folder', 'text' => 'Laporan','parent' => "#",'menu_level' => "1",],
               [ 'id_menu' => 71, 'id' => 'invoice-report',      'type' => 'file', 'text' => 'Laporan Invoice','parent' => "7",'menu_level' => "2",],
               [ 'id_menu' => 711, 'id' => 'invoice-report',      'type' => 'file', 'text' => 'Tagihan Invoice','parent' => "71",'menu_level' => "2",],
               [ 'id_menu' => 72, 'id' => '#',                     'type' => 'file', 'text' => 'Daftar Simp Berjangka Ditutup','parent' => "7",'menu_level' => "2",],
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