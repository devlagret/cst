<?php

namespace Database\Factories;

use Carbon\Carbon;
use App\Models\CoreClient;
use App\Models\CoreProduct;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CoreProduct>
 */
class CoreProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = CoreProduct::class;
    public function definition(): array
    {
        return [
            'name'              => $this->faker->word,
            'remark'         => $this->faker->sentence,
            'client_id' => CoreClient::all()->random()->client_id,
            'payment_period' => 3,
            'product_type_id' => $this->faker->numberBetween(1,3),
            'start_dev_date' => Carbon::parse($this->faker->dateTimeThisDecade('+2 years'))->format('Y-m-d'),
            'trial_date' => Carbon::parse($this->faker->dateTimeThisDecade('+2 years'))->format('Y-m-d'),
            'usage_date' => Carbon::parse($this->faker->dateTimeThisDecade('+2 years'))->format('Y-m-d'),
            'maintenance_date' => Carbon::parse($this->faker->dateTimeThisDecade('+2 years'))->format('Y-m-d'),
            'maintenance_price' => $this->faker->randomElement([10000,20000,50000,100000]),
            'payment_type' => $this->faker->randomElement([0,1]),
        ];
    }
}
