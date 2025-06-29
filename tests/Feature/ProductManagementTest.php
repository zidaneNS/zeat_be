<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_all_products() {
        $user = User::find(1);

        $response = $this->actingAs($user)->get('api/products');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'price',
                        'description',
                        'category' => [
                            'id',
                            'name'
                        ]
                    ]
                ]
            ]);

        $total_products = count($response['data']);

        $this->assertEquals($total_products, 10);
    }
}
