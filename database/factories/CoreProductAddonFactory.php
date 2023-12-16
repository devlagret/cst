<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CoreProductAddon>
 */
class CoreProductAddonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'   => $this->faker->word,
            'date'   => Carbon::parse($this->faker->dateTimeThisDecade('+2 years'))->format('Y-m-d'),
            'remark' => $this->faker->sentence,
            'amount' => $this->faker->randomElement([10000,20000,50000,100000]),
        ];
    }
}
