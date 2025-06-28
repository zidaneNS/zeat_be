<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_login() {
        $response = $this->postJson('api/login', [
            'email' => 'test@example.com',
            'password' => 'password'
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure(['status', 'message', 'data'])
            ->assertJson([
                'status' => 200,
                'message' => 'login succeed'
            ]);
    }

    public function test_can_register() {
        $response = $this->postJson('api/register', [
            'name' => 'zidane',
            'email' => 'zidane@example.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $response
            ->assertStatus(201)
            ->assertJsonStructure(['status', 'message'])
            ->assertJson([
                'status' => 201,
                'message' => 'register succeed'
            ]);

        $this
            ->assertDatabaseHas('users', [
                'email' => 'zidane@example.com'
            ]);
    }

    public function test_can_logout() {
        $user = User::find(1);
        
        $response = $this->actingAs($user)->get('api/logout');

        $response
            ->assertStatus(200)
            ->assertJsonStructure(['status', 'message'])
            ->assertJson([
                'status' => 200,
                'message' => 'logout succeed'
            ]);
    }

    public function test_can_get_user_information() {
        $user = User::find(1);

        $response = $this->actingAs($user)->get('api/user');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'id',
                    'name',
                    'email',
                    'phone_number',
                    'img_url'
                ]
            ])
            ->assertJson([
                'status' => 200,
                'message' => 'user information retrieved',
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email
                ]
            ]);
    }
}
