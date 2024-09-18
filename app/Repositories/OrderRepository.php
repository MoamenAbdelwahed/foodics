<?php

namespace App\Repositories;

use App\Models\Order;

class OrderRepository
{
    public function getAll()
    {
        return Order::all();
    }

    public function getById($id)
    {
        return Order::find($id);
    }

    public function create($data)
    {
        return Order::create($data);
    }

    public function update($id, $data)
    {
        $product = Order::find($id);
        $product->update($data);
        return $product;
    }

    public function delete($id)
    {
        return Order::destroy($id);
    }

    public function attachProduct($order, $productId, $quantity)
    {
        $order->products()->attach($productId, ['quantity' => $quantity]);
    }
}
