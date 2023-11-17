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
        if(!Schema::hasTable('acct_account')) {
            Schema::create('acct_account', function (Blueprint $table) {
                $table->id('account_id');
                $table->integer('account_type_id')->nullable();
                $table->string('account_code',20)->nullable();
                $table->string('account_name')->nullable();
                $table->string('account_group',20)->nullable();
                $table->boolean('account_default_status')->nullable()->default(0);
                $table->boolean('account_status')->nullable()->default(0);
                $table->boolean('account_suspended')->nullable()->default(0);
                $table->integer('parent_account_id')->nullable()->default(0);
                $table->integer('top_parent_account_id')->nullable()->default(0);
                $table->boolean('account_has_child')->nullable()->default(0);
                $table->decimal('opening_debit_balance',20)->nullable()->default(0);
                $table->decimal('opening_credit_balance',20)->nullable()->default(0);
                $table->decimal('debit_change',20)->nullable()->default(0);
                $table->decimal('credit_change',20)->nullable()->default(0);
                $table->text('account_remark')->nullable();
                $table->unsignedBigInteger('created_id')->nullable();
                $table->unsignedBigInteger('updated_id')->nullable();
                $table->unsignedBigInteger('deleted_id')->nullable();
                $table->timestamps();
                $table->softDeletesTz();
            });
             // Insert admin user
            DB::table('acct_account')->insert([
                ['account_type_id' => 0,'account_code' => "100",      'account_name' => "AKTIVA",                              'account_group' => "100",'account_status' => "0",'account_default_status' => "0"],
                ['account_type_id' => 0,'account_code' => "101.00",   'account_name' => "AKTIVA LANCAR",                       'account_group' => "101",'account_status' => "0",'account_default_status' => "0"],
                ['account_type_id' => 0,'account_code' => "101.01",   'account_name' => "Kas Besar",                           'account_group' => "101",'account_status' => "0",'account_default_status' => "0"],
                ['account_type_id' => 0,'account_code' => "101.02",   'account_name' => "Kas Di Bank",                         'account_group' => "101",'account_status' => "0",'account_default_status' => "0"],
                ['account_type_id' => 0,'account_code' => "101.02.1", 'account_name' => "Kas Di Bank MANDIRI",                 'account_group' => "101.02",'account_status' => "0",'account_default_status' => "0"],
                ['account_type_id' => 0,'account_code' => "101.02.2", 'account_name' => "Kas Di BNI",                          'account_group' => "101.02",'account_status' => "0",'account_default_status' => "0"],
                ['account_type_id' => 0,'account_code' => "101.02.3", 'account_name' => "Kas di BRI",                          'account_group' => "101.02",'account_status' => "0",'account_default_status' => "0"],
                ['account_type_id' => 0,'account_code' => "101.03",   'account_name' => "Piutang",                             'account_group' => "101",'account_status' => "0",'account_default_status' => "0"],
                ['account_type_id' => 0,'account_code' => "101.03.1", 'account_name' => "Piutang Aplikasi",                    'account_group' => "101.03",'account_status' => "0",'account_default_status' => "0"],
                ['account_type_id' => 0,'account_code' => "101.03.2", 'account_name' => "Piutang Hardware",                    'account_group' => "101.03",'account_status' => "0",'account_default_status' => "0"],
                ['account_type_id' => 0,'account_code' => "101.03.3", 'account_name' => "Piutang Divisi Multimedia",           'account_group' => "101.03",'account_status' => "0",'account_default_status' => "0"],
                ['account_type_id' => 0,'account_code' => "101.03.4", 'account_name' => "Piutang Dagang Device",               'account_group' => "101.03",'account_status' => "0",'account_default_status' => "0"],
                ['account_type_id' => 0,'account_code' => "101.03.5", 'account_name' => "Piutang Lain-lain",                   'account_group' => "101.03",'account_status' => "0",'account_default_status' => "0"],
                ['account_type_id' => 0,'account_code' => "102.00",   'account_name' => "AKTIVA TETAP",                        'account_group' => "102",'account_status' => "0",'account_default_status' => "0"],
                ['account_type_id' => 0,'account_code' => "102.01",   'account_name' => "Inventaris Hardware",                 'account_group' => "102",'account_status' => "0",'account_default_status' => "0"],
                ['account_type_id' => 0,'account_code' => "102.02",   'account_name' => "Inventaris Kantor/Pameran",           'account_group' => "102",'account_status' => "0",'account_default_status' => "0"],
                ['account_type_id' => 0,'account_code' => "103.00",   'account_name' => "Akumulasi Penyusutan",                'account_group' => "103",'account_status' => "1",'account_default_status' => "1"],
                ['account_type_id' => 0,'account_code' => "103.01",   'account_name' => "Akumulasi Penyusutan Hardware",       'account_group' => "103",'account_status' => "1",'account_default_status' => "1"],
                ['account_type_id' => 0,'account_code' => "103.02",   'account_name' => "Akumulasi Penyusutan Kantor",         'account_group' => "103",'account_status' => "1",'account_default_status' => "1"],
                ['account_type_id' => 1,'account_code' => "200",      'account_name' => "PASIVA",                              'account_group' => "200",'account_status' => "0",'account_default_status' => "0"],
                ['account_type_id' => 1,'account_code' => "201.00",   'account_name' => "KEWAJIBAN LANCAR",                    'account_group' => "201",'account_status' => "0",'account_default_status' => "0"],
                ['account_type_id' => 1,'account_code' => "201.01",   'account_name' => "Utang Dagang",                        'account_group' => "201",'account_status' => "1",'account_default_status' => "1"],
                ['account_type_id' => 1,'account_code' => "201.02",   'account_name' => "Utang Pajak",                         'account_group' => "201",'account_status' => "1",'account_default_status' => "1"],
                ['account_type_id' => 1,'account_code' => "201.03",   'account_name' => "Utang Lain-lain",                     'account_group' => "201",'account_status' => "1",'account_default_status' => "1"],
                ['account_type_id' => 1,'account_code' => "201.04",   'account_name' => "Utang Gaji",                          'account_group' => "201",'account_status' => "1",'account_default_status' => "1"],
                ['account_type_id' => 1,'account_code' => "202.00",   'account_name' => "MODAL/EKUITAS",                       'account_group' => "202",'account_status' => "0",'account_default_status' => "0"],
                ['account_type_id' => 1,'account_code' => "202.01",   'account_name' => "L/R Tahun Berjalan",                  'account_group' => "202",'account_status' => "1",'account_default_status' => "1"],
                ['account_type_id' => 1,'account_code' => "202.02",   'account_name' => "Modal Penyertaan",                    'account_group' => "202",'account_status' => "1",'account_default_status' => "1"],
                ['account_type_id' => 1,'account_code' => "202.03",   'account_name' => "L/R Tahun Lalu",                      'account_group' => "202",'account_status' => "1",'account_default_status' => "1"],
                ['account_type_id' => 1,'account_code' => "202.04",   'account_name' => "PRIVE PMS",                           'account_group' => "202",'account_status' => "1",'account_default_status' => "1"],
                ['account_type_id' => 2,'account_code' => "300",      'account_name' => "PENDAPATAN",                          'account_group' => "300",'account_status' => "0",'account_default_status' => "0"],
                ['account_type_id' => 2,'account_code' => "300.01",   'account_name' => "Pendapatan Software",                 'account_group' => "300",'account_status' => "1",'account_default_status' => "1"],
                ['account_type_id' => 2,'account_code' => "300.02",   'account_name' => "Pendapatan Penjualan Hardware",       'account_group' => "300",'account_status' => "1",'account_default_status' => "1"],
                ['account_type_id' => 2,'account_code' => "300.03",   'account_name' => "Pendapatan Divisi Multimedia",        'account_group' => "300",'account_status' => "1",'account_default_status' => "1"],
                ['account_type_id' => 2,'account_code' => "300.04",   'account_name' => "Pendapatan Jasa Lainnya",             'account_group' => "300",'account_status' => "1",'account_default_status' => "1"],
                ['account_type_id' => 2,'account_code' => "300.05",   'account_name' => "Pendapatan Lainnya",                  'account_group' => "300",'account_status' => "1",'account_default_status' => "1"],
                ['account_type_id' => 3,'account_code' => "400",      'account_name' => "BEBAN",                               'account_group' => "400",'account_status' => "0",'account_default_status' => "0"],
                ['account_type_id' => 3,'account_code' => "401.00",   'account_name' => "Beban OPERASIONAL",                   'account_group' => "401",'account_status' => "0",'account_default_status' => "0"],
                ['account_type_id' => 3,'account_code' => "401.01",   'account_name' => "Beban Tenaga Kerja",                  'account_group' => "401",'account_status' => "0",'account_default_status' => "0"],
                ['account_type_id' => 3,'account_code' => "401.02",   'account_name' => "Beban BPJSTK",                        'account_group' => "401",'account_status' => "0",'account_default_status' => "0"],
                ['account_type_id' => 3,'account_code' => "401.03",   'account_name' => "Beban Server, Domain",                'account_group' => "401",'account_status' => "0",'account_default_status' => "0"],
                ['account_type_id' => 3,'account_code' => "401.04",   'account_name' => "Beban Kontrak Aplikasi",              'account_group' => "401",'account_status' => "0",'account_default_status' => "0"],
                ['account_type_id' => 3,'account_code' => "401.05",   'account_name' => "Beban Listrik, Internet, Komunikasi", 'account_group' => "401",'account_status' => "0",'account_default_status' => "0"],
                ['account_type_id' => 3,'account_code' => "401.06",   'account_name' => "Beban ATK",                           'account_group' => "401",'account_status' => "0",'account_default_status' => "0"],
                ['account_type_id' => 3,'account_code' => "401.07",   'account_name' => "Beban  Perjalanan Dinas",             'account_group' => "401",'account_status' => "0",'account_default_status' => "0"],
                ['account_type_id' => 3,'account_code' => "401.08",   'account_name' => "Beban Tenaga Teknisi",                'account_group' => "401",'account_status' => "0",'account_default_status' => "0"],
                ['account_type_id' => 3,'account_code' => "401.09",   'account_name' => "Beban Komisi Penjualan",              'account_group' => "401",'account_status' => "0",'account_default_status' => "0"],
                ['account_type_id' => 3,'account_code' => "401.10",   'account_name' => "Beban Share Fee",                     'account_group' => "401",'account_status' => "0",'account_default_status' => "0"],
                ['account_type_id' => 3,'account_code' => "402.00",   'account_name' => "Beban NON OPERASIONAL",               'account_group' => "402",'account_status' => "0",'account_default_status' => "0"],
                ['account_type_id' => 3,'account_code' => "401.01",   'account_name' => "Beban Pembelian Pantry",              'account_group' => "401",'account_status' => "0",'account_default_status' => "0"],
                ['account_type_id' => 3,'account_code' => "401.02",   'account_name' => "Beban Marketing dan Promosi",         'account_group' => "401",'account_status' => "0",'account_default_status' => "0"],
                ['account_type_id' => 3,'account_code' => "401.03",   'account_name' => "Beban Maintenance/Service",           'account_group' => "401",'account_status' => "0",'account_default_status' => "0"],
                ['account_type_id' => 3,'account_code' => "401.04",   'account_name' => "Beban Entertaintment",                'account_group' => "401",'account_status' => "0",'account_default_status' => "0"],
                ['account_type_id' => 3,'account_code' => "403.00",   'account_name' => "Beban DI LUAR USAHA",                 'account_group' => "403",'account_status' => "0",'account_default_status' => "0"],
                ['account_type_id' => 3,'account_code' => "403.01",   'account_name' => "Beban Perijinan",                     'account_group' => "403",'account_status' => "0",'account_default_status' => "0"],
                ['account_type_id' => 3,'account_code' => "403.02",   'account_name' => "Beban Sumbangan",                     'account_group' => "403",'account_status' => "0",'account_default_status' => "0"],
                ['account_type_id' => 3,'account_code' => "403.03",   'account_name' => "Beban Entertaintment",                'account_group' => "403",'account_status' => "0",'account_default_status' => "0"],
                ['account_type_id' => 3,'account_code' => "403.04",   'account_name' => "Beban Iuran Lain - Lain",             'account_group' => "403",'account_status' => "0",'account_default_status' => "0"],
                ['account_type_id' => 3,'account_code' => "410.00",   'account_name' => "Rugi / Laba Sebelum Pajak",           'account_group' => "410",'account_status' => "0",'account_default_status' => "0"],
            ]);
        }
    }
   
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('acct_account');
    }
};
