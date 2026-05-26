<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\TestResult;

class CartTest extends TestCase
{
    public function test_user_can_add_to_cart()
    {
        $this->withoutMiddleware();

        $user = User::factory()->create();

        $product = Product::factory()->create();

        Inventory::create([
            'product_id' => $product->id,
            'quantity' => 10,
            'sold_quantity' => 0,
            'status' => 'in_stock'
        ]);

        $response = $this->actingAs($user)
            ->post('/cart/add', [
                'product_id' => $product->id,
                'quantity' => 1,
                'size' => '42'
            ]);

        $response->assertStatus(200);

        $response->assertJson([
            'success' => true
        ]);

        TestResult::create([
            'test_case' => 'Cart Test',
            'result' => 'Thêm giỏ hàng thành công',
            'status' => 'PASS'
        ]);
    }
}