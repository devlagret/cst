<?php

namespace Database\Factories;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class AssetMenuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = \Faker\Factory::create();
        
        $twoYearsAgo = Carbon::now()->subYears(2); 
        $currentDate = Carbon::now(); 
        $randomDate = $faker->dateTimeBetween($twoYearsAgo, $currentDate)->format('Y-m-d');
        return [
            'name' => $this->faker->name,
            'buy_date' => $randomDate,
            'price' => $this->faker->numberBetween(1000, 100000),
            'acquisition_amount' => $this->faker->numberBetween(1, 10),
            'estimated_age' => $this->faker->numberBetween(17, 50),
            'residual_amount' => $this->faker->numberBetween(10, 100),
            'remark' => $this->faker->sentence,
            'created_id' => null,
            'updated_id' => null,
            'deleted_id' => null,
        ];
 
    }
}
