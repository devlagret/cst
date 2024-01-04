<?php

namespace Database\Seeders;

use App\Models\AcctInvoice;
use App\Models\AcctInvoiceItem;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AcctInvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AcctInvoice::factory()->count(5)
        ->has(AcctInvoiceItem::factory()->count(3),'items')
        ->create();
    }
}