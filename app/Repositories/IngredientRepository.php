<?php

namespace App\Repositories;

use App\Models\Ingredient;

class IngredientRepository
{
    public function getAll()
    {
        return Ingredient::all();
    }

    public function getById($id)
    {
        return Ingredient::find($id);
    }

    public function create($data)
    {
        return Ingredient::create($data);
    }

    public function update($id, $data)
    {
        $product = Ingredient::find($id);
        $product->update($data);
        return $product;
    }

    public function delete($id)
    {
        return Ingredient::destroy($id);
    }

    public function save(Ingredient $ingredient)
    {
        $ingredient->save();
    }
}
