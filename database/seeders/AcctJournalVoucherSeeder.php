<?php

namespace Database\Seeders;

use App\Models\AcctJournalVoucherItem;
use Illuminate\Database\Seeder;
use App\Models\AcctJournalVoucher;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AcctJournalVoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        AcctJournalVoucher::factory()->count(5)
        ->has(AcctJournalVoucherItem::factory()->count(1),'items')
        ->create();
    }
}