<?php

namespace Database\Seeders;

use App\Models\AcctBankAccount;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AcctBankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        AcctBankAccount::factory()->count(5)->create();
    }
}
