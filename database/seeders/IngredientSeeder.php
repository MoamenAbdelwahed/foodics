<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Ingredient::create([
            'name' => 'Beef',
            'stock' => 20000,
            'initial_stock' => 20000
        ]);

        Ingredient::create([
            'name' => 'Cheese',
            'stock' => 5000,
            'initial_stock' => 5000
        ]);

        Ingredient::create([
            'name' => 'Onion',
            'stock' => 1000,
            'initial_stock' => 1000
        ]);
    }
}
