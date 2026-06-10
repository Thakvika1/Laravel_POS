<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminManagementAndRealtimeTest extends TestCase
{
    use RefreshDatabase;

    public function test_recent_products_endpoint_returns_latest_items_for_other_sessions(): void
    {
        $admin = User::create([
            'name' => 'Owner',
            'email' => 'owner-realtime@example.com',
            'password' => bcrypt('secret123'),
            'is_admin' => true,
            'is_system_admin' => true,
        ]);

        Product::create([
            'name' => 'Live Product',
            'description' => 'Visible instantly',
            'price' => 12.5,
            'qty' => 5,
            'category' => 'Drinks',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->getJson('/products/recent');

        $response->assertOk();
        $response->assertJsonFragment(['name' => 'Live Product']);
    }

    public function test_system_admin_can_delete_and_force_logout_other_admins(): void
    {
        $systemAdmin = User::create([
            'name' => 'System Admin',
            'email' => 'sysadmin@example.com',
            'password' => bcrypt('secret123'),
            'is_admin' => true,
            'is_system_admin' => true,
        ]);

        $otherAdmin = User::create([
            'name' => 'Manager',
            'email' => 'manager@example.com',
            'password' => bcrypt('secret123'),
            'is_admin' => true,
            'is_system_admin' => false,
        ]);

        $this->actingAs($systemAdmin)
            ->withSession(['_token' => 'test-token'])
            ->post(route('admin.users.logout', $otherAdmin), ['_token' => 'test-token'])
            ->assertRedirect();

        $this->assertDatabaseHas('users', ['id' => $otherAdmin->id, 'session_id' => null]);

        $this->actingAs($systemAdmin)
            ->withSession(['_token' => 'test-token'])
            ->delete(route('admin.users.destroy', $otherAdmin), ['_token' => 'test-token'])
            ->assertRedirect();

        $this->assertDatabaseMissing('users', ['id' => $otherAdmin->id]);
    }
}
