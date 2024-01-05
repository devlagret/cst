<?php

namespace Database\Seeders;

use App\Models\CoreProduct;
use App\Models\CoreProductAddon;
use App\Models\CoreProductTermin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CoreProduct::factory()->count(3)
        ->has(CoreProductTermin::factory()->count(3),'termin')
        ->has(CoreProductAddon::factory()->count(3),'addons')
        ->create();
    }
}