<?php

namespace Database\Factories;


use App\Models\CoreClient;
use App\Models\AcctInvoice;
use App\Models\CoreProduct;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AcctInvoice>
 */
class AcctInvoiceFactory extends Factory
{
    protected $model = AcctInvoice::class;
    
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        
        $faker = \Faker\Factory::create();

        $client = CoreClient::factory()->create();
        $product = CoreProduct::factory()->create();
        
        return [
            'invoice_date' => $faker->date(),
            'client_id' => $client->client_id,
            'product_id' => $product->product_id,
            'invoice_no' => $faker->unique()->word,
            'remark' => $faker->sentence,
            'subtotal_amount' => $faker->randomFloat(2, 100, 1000),
            'adjustment_amount' => $faker->randomFloat(2, 0, 100),
            'total_amount' => $faker->randomFloat(2, 100, 1000),
            'payed_amount' => $faker->randomFloat(2, 0, 100),
            'receivables_amount' => $faker->randomFloat(2, 0, 1000),
            'discount_percentage' => $faker->numberBetween(0, 10),
            'discount_amount' => $faker->randomFloat(2, 0, 100),
            'tax_ppn_percentage' => $faker->numberBetween(0, 10),
            'tax_ppn_amount' => $faker->randomFloat(2, 0, 100),
            'payment_type' => $faker->randomElement([0, 1]),
            'invoice_type' => $faker->randomElement([0, 3]), 
            'invoice_status' => $faker->randomElement([0, 1]), 
            'created_id' => $faker->numberBetween(1, 10), 
            'updated_id' => $faker->numberBetween(1, 10), 
        ];
    }
}