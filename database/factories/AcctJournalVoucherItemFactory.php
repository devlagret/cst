<?php

namespace Database\Factories;
use App\Models\AcctAccount;
use Faker\Generator as Faker;
use App\Models\AcctJournalVoucherItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AcctJournalVoucherItem>
 */
class AcctJournalVoucherItemFactory extends Factory
{
    public function definition(): array
    {
        $faker = \Faker\Factory::create();
        return [
            'account_id' => AcctAccount::factory()->create()->account_id,
            'journal_voucher_description' => $faker->sentence,
            'journal_voucher_amount' => $faker->randomFloat(2, 10, 1000),
            'account_id_status' => $faker->boolean,
            'account_id_default_status' => $faker->boolean,
            'journal_voucher_debit_amount' => $faker->randomFloat(2, 0, 500),
            'journal_voucher_credit_amount' => $faker->randomFloat(2, 0, 500),
            'reverse_state' => $faker->boolean,
            'created_id' => $faker->numberBetween(1, 10),
            'updated_id' => $faker->numberBetween(1, 10),
            'deleted_id' => null,
            'created_at' => $faker->dateTimeThisMonth,
            'updated_at' => $faker->dateTimeThisMonth,
            'deleted_at' => null,
        ];
    }
}