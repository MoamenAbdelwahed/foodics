<?php

namespace Tests\Unit;

use Tests\TestCase;
use Mockery;
use App\Services\OrderService;
use App\Repositories\ProductRepository;
use App\Repositories\OrderRepository;
use App\Repositories\IngredientRepository;
use Illuminate\Support\Facades\Mail;
use App\Mail\StockAlertMail;
use App\Models\Product;
use App\Models\Order;
use App\Models\Ingredient;

class OrderServiceTest extends TestCase
{
    protected $productRepo;
    protected $orderRepo;
    protected $ingredientRepo;
    protected $orderService;

    public function setUp(): void
    {
        parent::setUp();

        $this->productRepo = Mockery::mock(ProductRepository::class);
        $this->orderRepo = Mockery::mock(OrderRepository::class);
        $this->ingredientRepo = Mockery::mock(IngredientRepository::class);

        $this->orderService = new OrderService(
            $this->productRepo, 
            $this->orderRepo, 
            $this->ingredientRepo
        );

        Mail::fake();
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_creates_an_order_and_attaches_products()
    {
        $productData = [
            ['product_id' => 1, 'quantity' => 2]
        ];

        $product = Mockery::mock(Product::class);
        
        $product->shouldReceive('getAttribute')->with('id')->andReturn(1);
        
        $ingredient = new Ingredient(['pivot' => ['amount' => 150], 'stock' => 20000]);
        $product->shouldReceive('getAttribute')->with('ingredients')->andReturn(collect([$ingredient]));

        $order = Mockery::mock(Order::class);

        $this->orderRepo->shouldReceive('create')->once()->andReturn($order);
        $this->productRepo->shouldReceive('findById')->once()->with(1)->andReturn($product);
        $this->orderRepo->shouldReceive('attachProduct')->once()->with($order, 1, 2);

        $result = $this->orderService->placeOrder($productData);

        $this->assertEquals($order, $result);
    }


    /** @test */
    public function it_updates_ingredient_stock_and_sends_alert_if_needed()
    {
        $productData = [
            ['product_id' => 1, 'quantity' => 2]
        ];

        $product = Mockery::mock(Product::class);
        $product->shouldReceive('getAttribute')->with('id')->andReturn(1);
        
        $ingredient = new Ingredient(['pivot' => ['amount' => 150], 'stock' => 500, 'initial_stock' => 20000, 'alert_sent' => false]);

        $product->shouldReceive('getAttribute')->with('ingredients')->andReturn(collect([$ingredient]));
        $order = Mockery::mock(Order::class);

        $this->orderRepo->shouldReceive('create')->once()->andReturn($order);
        $this->productRepo->shouldReceive('findById')->once()->with(1)->andReturn($product);
        $this->orderRepo->shouldReceive('attachProduct')->once()->with($order, 1, 2);
        $this->ingredientRepo->shouldReceive('save')->twice();

        $this->orderService->placeOrder($productData);

        Mail::assertSent(StockAlertMail::class, function ($mail) use ($ingredient) {
            return $mail->ingredient->stock === $ingredient->stock;
        });
    }

    public function it_does_not_send_stock_alert_if_stock_is_above_threshold()
    {
        $productData = [
            ['product_id' => 1, 'quantity' => 1]
        ];

        $product = Mockery::mock(Product::class);
        $product->id = 1;
        
        $ingredient = new Ingredient(['pivot' => ['amount' => 100], 'stock' => 15000, 'initial_stock' => 20000, 'alert_sent' => false]);

        $product->ingredients = collect([$ingredient]);
        $order = Mockery::mock(Order::class);

        $this->orderRepo->shouldReceive('create')->once()->andReturn($order);
        $this->productRepo->shouldReceive('findById')->once()->with(1)->andReturn($product);
        $this->orderRepo->shouldReceive('attachProduct')->once()->with($order, 1, 1);
        $this->ingredientRepo->shouldReceive('save')->once();

        $this->orderService->placeOrder($productData);

        Mail::assertNotSent(StockAlertMail::class);
    }
}
