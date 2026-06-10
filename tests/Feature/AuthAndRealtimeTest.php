<?php

namespace Tests\Feature;

use App\Models\Notification;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthAndRealtimeTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_when_visiting_products(): void
    {
        $response = $this->get('/products');

        $response->assertRedirect('/login');
    }

    public function test_admin_can_login_and_create_another_admin(): void
    {
        $admin = User::create([
            'name' => 'Owner',
            'email' => 'owner@example.com',
            'password' => bcrypt('secret123'),
            'is_admin' => true,
        ]);

        $response = $this->withSession(['_token' => 'test-token'])->post('/login', [
            '_token' => 'test-token',
            'email' => 'owner@example.com',
            'password' => 'secret123',
        ]);

        $response->assertRedirect('/products');
        $this->assertAuthenticatedAs($admin);

        $response = $this->actingAs($admin)->withSession(['_token' => 'test-token'])->post('/admin/users', [
            '_token' => 'test-token',
            'name' => 'Manager',
            'email' => 'manager@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/admin/users');
        $this->assertDatabaseHas('users', ['email' => 'manager@example.com', 'is_admin' => true]);
    }

    public function test_product_creation_creates_live_notification(): void
    {
        $admin = User::create([
            'name' => 'Owner',
            'email' => 'owner2@example.com',
            'password' => bcrypt('secret123'),
            'is_admin' => true,
        ]);

        $response = $this->actingAs($admin)->withSession(['_token' => 'test-token'])->post('/products', [
            '_token' => 'test-token',
            'name' => 'Coffee',
            'description' => 'Fresh coffee',
            'price' => 4.5,
            'qty' => 10,
            'category' => 'Drinks',
            'is_active' => true,
        ]);

        $response->assertRedirect('/products');
        $this->assertDatabaseHas('notifications', ['type' => 'product_created']);
        $this->assertTrue(Notification::where('type', 'product_created')->exists());
    }
}
