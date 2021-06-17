<?php

namespace Database\Seeders;

use App\Models\Invoice;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        foreach (range(1, 50) as $index) {
            Invoice::create([
                'invoice_number' => $faker->randomDigit,
                'invoice_date' => $faker->date(),
                'due_date' => $faker->date(),
                'section_id' => $faker->date(),
                'product' => $faker->word,
                'amount_collection' => $faker->randomNumber(),
                'amount_commission' => $faker->randomNumber(),
                'discount' => $faker->randomFloat(1000, 10000),
                'value_vat' => $faker->randomFloat(2000, 5000),
                'rate_vat' => $faker->randomElement(5, 10),
                'total' => $faker->randomNumber(),
                'note' => $faker->sentence(),
            ]);
        }
    }
}
