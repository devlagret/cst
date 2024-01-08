<?php

namespace Database\Factories;
use App\Models\AcctAccount;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AcctAccount>
 */
class AcctAccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = AcctAccount::class;

    public function definition(): array
    {
        $faker = \Faker\Factory::create();

        return [
            'account_type_id' => $faker->numberBetween(0, 3),
            'account_code' => $faker->numerify('###.##'),
            'account_name' => $faker->word,
            'account_group' => $faker->numerify('###'),
            'account_default_status' => $faker->boolean,
            'account_status' => $faker->boolean,
            'account_suspended' => $faker->boolean,
            'parent_account_id' => $faker->numberBetween(0, 100),
            'top_parent_account_id' => $faker->numberBetween(0, 100),
            'account_has_child' => $faker->boolean,
            'opening_debit_balance' => $faker->randomFloat(2, 0, 10000),
            'opening_credit_balance' => $faker->randomFloat(2, 0, 10000),
            'debit_change' => $faker->randomFloat(2, 0, 10000),
            'credit_change' => $faker->randomFloat(2, 0, 10000),
            'account_remark' => $faker->paragraph,
            'created_id' => $faker->numberBetween(1, 10),
            'updated_id' => $faker->numberBetween(1, 10),
            'deleted_id' => null,
            'created_at' => $faker->dateTimeThisMonth,
            'updated_at' => $faker->dateTimeThisMonth,
            'deleted_at' => null,
        ];
    }
}