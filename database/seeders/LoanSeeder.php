<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Loan;
use Faker\Factory as Faker;

class LoanSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 20) as $index) {
            Loan::create([
                'code' => $faker->unique()->bothify('LOAN-####'),
                'date' => $faker->date(),
                'employee_name' => $faker->name,
                'company' => $faker->company,
                'department' => $faker->word,
                'designation' => $faker->jobTitle,
                'loan_start_date' => $faker->date(),
                'loan_end_date' => $faker->date(),
                'loan_amount' => $faker->randomFloat(2, 1000, 10000),
                'per_installment_amount' => $faker->randomFloat(2, 100, 500),
                'number_of_installments' => $faker->numberBetween(6, 24),
                'loan_deduction_amount' => $faker->randomFloat(2, 100, 1000),
                'loan_paid' => $faker->randomFloat(2, 100, 5000),
                'balance' => $faker->randomFloat(2, 0, 5000),
                'remarks' => $faker->sentence(),
                'auto_deduction' => $faker->boolean,
            ]);
        }
    }
}
