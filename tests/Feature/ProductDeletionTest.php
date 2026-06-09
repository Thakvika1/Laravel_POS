<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductDeletionTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_with_order_items_cannot_be_deleted(): void
    {
        $product = Product::create([
            'name' => 'Test Product',
            'price' => 25.50,
            'qty' => 10,
            'description' => 'A test product',
            'category' => 'Tools',
            'is_active' => true,
        ]);

        $order = Order::create([
            'order_number' => 'ORD-20240609-0001',
            'customer_name' => 'Jane Doe',
            'subtotal' => 25.50,
            'tax' => 0,
            'discount' => 0,
            'total' => 25.50,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'unit_price' => $product->price,
            'quantity' => 1,
            'subtotal' => $product->price,
        ]);

        $this->withoutMiddleware([VerifyCsrfToken::class, ValidateCsrfToken::class]);

        $response = $this->from(route('products.index'))->delete(route('products.destroy', $product));

        $response->assertRedirect(route('products.index'));
        $this->assertSame('Product cannot be deleted because it has associated orders.', session('error'));
        $this->assertDatabaseHas('products', ['id' => $product->id]);
        $this->assertDatabaseHas('order_items', ['product_id' => $product->id]);
    }
}
