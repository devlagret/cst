<?php

namespace Database\Seeders;

use App\Models\AssetMenu;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        AssetMenu::factory()->count(5)->create();
    }
}
