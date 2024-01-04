<?php

namespace Database\Factories;

use App\Models\CoreClient;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CoreClient>
 */
class CoreClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = \Faker\Factory::create();

        return [
            'name' => $faker->company,
            'address' => $faker->address,
            'contact_person' => $faker->name,
            'phone' => $faker->phoneNumber,
            'email' => $faker->unique()->safeEmail,
            'client_token' => $faker->uuid,
            'created_id' => $faker->numberBetween(1, 10),
            'updated_id' => $faker->numberBetween(1, 10),
            'deleted_id' => null,
            'created_at' => $faker->dateTimeThisMonth,
            'updated_at' => $faker->dateTimeThisMonth,
            'deleted_at' => null,
        ];
    }
}