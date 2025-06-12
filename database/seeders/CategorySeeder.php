<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        //
        $categories = [
            ['name' => 'Salary', 'type' => 'cash_in'],
            ['name' => 'Cash Back', 'type' => 'cash_in'],
            ['name' => 'Refund', 'type' => 'cash_in'],
            ['name' => 'Gift/Support', 'type' => 'cash_in'],
            ['name' => 'Misc', 'type' => 'cash_in'],

            ['name' => 'Coffee', 'type' => 'cash_out'],
            ['name' => 'Food', 'type' => 'cash_out'],
            ['name' => 'Petrol', 'type' => 'cash_out'],
            ['name' => 'Movie', 'type' => 'cash_out'],
            ['name' => 'Rent', 'type' => 'cash_out'],
            ['name' => 'Travel', 'type' => 'cash_out'],
            ['name' => 'Shopping', 'type' => 'cash_out'],
            ['name' => 'Weekend Chills', 'type' => 'cash_out'],
            ['name' => 'Bills / Others', 'type' => 'cash_out'],

        ];

        foreach ($categories as $category) {
            Category::create($category);        
        }

    }
}
