<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        
        User::factory()->create([
            'name' => 'Itachi 😎',
            'email' => 'itachi@gmail.com',
            'password' => Hash::make('itachi@gmail.com'),
        ]);
        $this->call(CategorySeeder::class);
        $this->call(TransactionSeeder::class);
    }
}
