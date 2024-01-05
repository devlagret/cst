<?php

namespace Database\Seeders;

use App\Models\AcctInvoice;
use App\Models\AcctInvoiceItem;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AcctInvoiceItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AcctInvoiceItem::factory()->count(3)
        ->has(AcctInvoice::factory()->count(1),'invoice')
        ->create();
    }
}