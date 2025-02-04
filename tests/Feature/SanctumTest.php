<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

class SanctumTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login(): void
    {
        $user = User::factory()->create([
            'email' => 'wil@taka.com',
            'name' => 'wil',
        ]);

        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'password',]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'user' => ['email', 'name'],
                'token',
        ]);
    }

    public function test_user_can_see_auth_routes(): void
    {
        $user = User::factory()->create([
            'email' => 'wil@taka.com',
            'name' => 'wil',
        ]);

        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => 'password',]);

        // Fronted
        $token = $response->json('token');
        
        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->get('/api/user');
        
            // dd($response->json());
            $response->assertJson([
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
            ]);
    }

    public function test_user_can_request_with_permissons(): void
    {
        $user = User::factory()->create([
            'email' => 'wil@taka.com',
            'name' => 'wil',
        ]);

        Sanctum::actingAs($user, ['create-post']);

        $response = $this->getJson('/api/post/create', [
            'title' => 'Mi titulo',
            'content' => 'El contenido del post',
        ]);

       $response->assertStatus(200);
    }
}
