<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AcctBalanceSheetReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('acct_balance_sheet_report')->insert([
            [ 'report_no' => 1, 'account_id1'=>null,'account_code1' => null, 'account_name1' => 'AKTIVA',                    'account_id2'=>null,'account_code2'=>null,'account_name2'=>'PASIVA' ,    'report_type1'=>1,'report_tab1'=>0 ,'report_bold1'=>1,  'report_type2'=>1,'report_tab2'=>0 ,'report_bold2'=>1,   'balance_report_type'=>0,'balance_report_type1'=>0],
            [ 'report_no' => 2, 'account_id1'=>1,'account_code1' => null, 'account_name1' => 'AKTIVA LANCAR',                    'account_id2'=>1,'account_code2'=>null,'account_name2'=>'KEWAJIBAN LANCAR' ,   'report_type1'=>2,'report_tab1'=>0 ,'report_bold1'=>1,   'report_type2'=>2,'report_tab2'=>0 ,'report_bold2'=>1,  'balance_report_type'=>0,'balance_report_type1'=>0],
        ]);
    }
}
