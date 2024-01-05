<?php

namespace Database\Factories;
use Carbon\Carbon;
use App\Models\AcctInvoice;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use App\Models\AcctInvoiceItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AcctInvoiceItem>
 */
class AcctInvoiceItemFactory extends Factory
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
            'payment_date' =>Carbon::parse($this->faker->dateTimeThisDecade('+2 years'))->format('Y-m-d'),
            'item_id' => null, 
            'account_id' => null, 
            'remark' => $faker->sentence(),
            'quantity' => $faker->numberBetween(1, 10),
            'item_unit_id' => null, 
            'unit' => null,
            'subtotal_amount' => $faker->randomFloat(2, 100, 1000), 
            'total_amount' => $faker->randomFloat(2, 100, 1100),
            'discount_percentage' => $faker->numberBetween(0, 10),
            'discount_amount' => $faker->randomFloat(2, 0, 50),
            'payment_type' => $faker->randomElement([0, 1]),
            'payment_status' => $faker->randomElement([0, 1]),
            'invoice_type' => $faker->randomElement([0, 1, 2, 3]),
            'invoice_status' => $faker->randomElement([0, 1]),
            'created_id' => 1, 
            'updated_id' => null,
            'deleted_id' => null,
            'created_at' => $faker->dateTimeBetween('-2 years', 'now'),
            'updated_at' => $faker->dateTimeBetween('-2 years', 'now'),
            // Jika terdapat field 'deleted_at', kamu bisa mengatur secara acak untuk mensimulasikan soft delete
            //'deleted_at' => $faker->optional(0.1)->dateTimeBetween('-1 year', 'now')
        ];
    }
}