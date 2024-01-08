<?php

namespace Database\Factories;

use App\Models\AcctAccount;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class AcctBankAccountFactory extends Factory
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
            //
            'bank_account_id' => $faker->numberBetween(0, 3),
            'bank_account_code' => $this->faker->bankAccountNumber,
            'bank_account_name' => $this->faker->company,
            'bank_account_no' => $this->faker->bankAccountNumber,
            'account_id' => function () {
                return AcctAccount::factory()->create()->account_id;
            },
        ];
    }
}
