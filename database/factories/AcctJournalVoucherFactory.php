<?php

namespace Database\Factories;


use App\Models\AcctJournalVoucher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AcctJournalVoucher>
 */
class AcctJournalVoucherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = AcctJournalVoucher::class;

    public function definition(): array
    {
        $faker = \Faker\Factory::create();


        return [
            'journal_voucher_status' => $faker->numberBetween(0, 1),
            'transaction_module_code' => $faker->word,
            'transaction_journal_id' => $faker->numberBetween(1, 100),
            'transaction_journal_no' => $faker->word,
            'journal_voucher_no' => $faker->word,
            'journal_voucher_period' => $faker->word,
            'journal_voucher_date' => $faker->date(),
            'journal_voucher_title' => $faker->sentence,
            'journal_voucher_description' => $faker->paragraph,
            'journal_voucher_token' => $faker->word,
            'reverse_state' => $faker->boolean,
            'pickup_status' => $faker->boolean,
            'created_id' => $faker->numberBetween(1, 10),
            'updated_id' => $faker->numberBetween(1, 10),
            'deleted_id' => null,
            'created_at' => $faker->dateTimeThisMonth,
            'updated_at' => $faker->dateTimeThisMonth,
            'deleted_at' => null,
        ];
    }
}