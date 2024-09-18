<?php
namespace App\Services;

use App\Repositories\ProductRepository;
use App\Repositories\OrderRepository;
use App\Repositories\IngredientRepository;
use Illuminate\Support\Facades\Mail;
use App\Mail\StockAlertMail;
use App\Models\Ingredient;

class OrderService
{
    protected $productRepo;
    protected $orderRepo;
    protected $ingredientRepo;

    public function __construct(
        ProductRepository $productRepo, 
        OrderRepository $orderRepo, 
        IngredientRepository $ingredientRepo
    ) {
        $this->productRepo = $productRepo;
        $this->orderRepo = $orderRepo;
        $this->ingredientRepo = $ingredientRepo;
    }

    public function placeOrder($products)
    {
        $order = $this->orderRepo->create([]);

        foreach ($products as $productData) {
            $product = $this->productRepo->findById($productData['product_id']);
            $quantity = $productData['quantity'];
            
            $this->orderRepo->attachProduct($order, $product->id, $quantity);

            foreach ($product->ingredients as $ingredient) {
                if ($ingredient->pivot) {
                    $amountNeeded = $ingredient->pivot->amount * $quantity;
                    $ingredient->stock -= $amountNeeded;
                    $this->ingredientRepo->save($ingredient);
                }

                if ($this->shouldSendStockAlert($ingredient)) {
                    $this->sendStockAlert($ingredient);
                    $ingredient->alert_sent = true;
                    $this->ingredientRepo->save($ingredient);
                }
            }
        }

        return $order;
    }

    private function shouldSendStockAlert(Ingredient $ingredient)
    {
        return $ingredient->stock < ($ingredient->initial_stock / 2) && !$ingredient->alert_sent;
    }

    private function sendStockAlert(Ingredient $ingredient)
    {
        Mail::to(env('MANAGEMENT_EMAIL'))->send(new StockAlertMail($ingredient));
    }
}