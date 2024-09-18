<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $burger = Product::create([
            'name' => 'Burger'
        ]);

        $ingredients = [
            'Beef' => 150,
            'Cheese' => 30,
            'Onion' => 20
        ];

        foreach ($ingredients as $ingredientName => $quantity) {
            $ingredient = Ingredient::where('name', $ingredientName)->first();
            $burger->ingredients()->attach($ingredient->id, ['quantity' => $quantity]);
        }
    }
}
