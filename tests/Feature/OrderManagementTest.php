<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_orders() {
        $user = User::find(1);

        $product = Product::find(1);

        $response = $this->actingAs($user)->postJson('api/orders', [
            'product_id' => $product->id,
            'quantity' => 3
        ]);

        $response
            ->assertStatus(201)
            ->assertJsonStructure(['status', 'message'])
            ->assertJson([
                'status' => 201,
                'message' => 'order successfully created'
            ]);
        
        $this
            ->assertDatabaseCount('orders', 1)
            ->assertDatabaseHas('orders', [
                'product_id' => $product->id,
                'user_id' => $user->id,
                'quantity' => 3,
                'status' => 'pending'
            ]);
    }

    public function test_can_get_all_orders_by_user() {
        $user = User::find(1);

        $product = Product::find(1);

        $this->actingAs($user)->postJson('api/orders', [
            'product_id' => $product->id,
            'quantity' => 3
        ]);

        $response = $this->actingAs($user)->get('api/orders');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'product_id',
                        'user_id',
                        'quantity',
                        'status',
                        'payment_method'
                    ]
                ]
                    ]);
        
        $total_orders = count($response['data']);

        $this->assertEquals($total_orders, 1);
    }

    public function test_can_checkout() {
        $user = User::find(1);

        $product = Product::find(1);

        $this->actingAs($user)->postJson('api/orders', [
            'product_id' => $product->id,
            'quantity' => 3
        ]);

        $order = Order::find(1);

        $response = $this->actingAs($user)->get('api/orders/' . $order->id);

        $response
            ->assertStatus(200)
            ->assertJsonStructure(['status', 'message'])
            ->assertJson([
                'status' => 200,
                'message' => 'checkout success'
            ]);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'product_id' => $product->id,
            'user_id' => $user->id,
            'status' => 'success'
        ]);
    }

    public function test_can_update_order_information() {
        $user = User::find(1);

        $product = Product::find(1);

        $this->actingAs($user)->postJson('api/orders', [
            'product_id' => $product->id,
            'quantity' => 3
        ]);

        $order = Order::find(1);

        $response = $this->actingAs($user)->putJson('api/orders/' . $order->id, [
            'quantity' => 1
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'id',
                    'product_id',
                    'user_id',
                    'quantity',
                    'payment_method',
                    'status'
                ]
            ])
            ->assertJson([
                'status' => 200,
                'message' => 'order successfully updated',
                'data' => [
                    'id' => $order->id,
                    'product_id' => $product->id,
                    'user_id' => $user->id,
                    'quantity' => 1,
                    'status' => 'pending'
                ]
            ]);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'product_id' => $product->id,
            'user_id' => $user->id,
            'quantity' => 1
        ]);
    }

    public function test_can_delete_order() {
        $user = User::find(1);

        $product = Product::find(1);

        $this->actingAs($user)->postJson('api/orders', [
            'product_id' => $product->id,
            'quantity' => 3
        ]);

        $order = Order::find(1);
        
        $response = $this->actingAs($user)->delete('api/orders/' . $order->id);

        $response
            ->assertStatus(200)
            ->assertJsonStructure(['status', 'message'])
            ->assertJson([
                'status' => 200,
                'message' => 'order successfully deleted'
            ]);

        $this->assertDatabaseMissing('orders', [
            'product_id' => $product->id,
            'user_id' => $user->id
        ]);
    }
}
