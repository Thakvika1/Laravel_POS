<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
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

    public function test_regular_admin_can_update_their_own_credentials_but_not_other_admins(): void
    {
        $manager = User::create([
            'name' => 'Manager',
            'email' => 'manager@example.com',
            'password' => bcrypt('secret123'),
            'is_admin' => true,
            'is_system_admin' => false,
        ]);

        $otherAdmin = User::create([
            'name' => 'Assistant Admin',
            'email' => 'assistant@example.com',
            'password' => bcrypt('secret123'),
            'is_admin' => true,
            'is_system_admin' => false,
        ]);

        $this->actingAs($manager)
            ->withSession(['_token' => 'test-token'])
            ->patch(route('admin.users.update', $manager), [
                '_token' => 'test-token',
                'name' => 'Manager Updated',
                'email' => 'manager-updated@example.com',
                'password' => 'newPassword123',
            ])
            ->assertRedirect();

        $manager->refresh();
        $this->assertSame('Manager Updated', $manager->name);
        $this->assertSame('manager-updated@example.com', $manager->email);
        $this->assertTrue(Hash::check('newPassword123', $manager->password));

        $this->actingAs($manager)
            ->withSession(['_token' => 'test-token'])
            ->patch(route('admin.users.update', $otherAdmin), [
                '_token' => 'test-token',
                'name' => 'Attempted Change',
                'email' => 'attempted@example.com',
                'password' => 'anotherPassword123',
            ])
            ->assertForbidden();

        $otherAdmin->refresh();
        $this->assertSame('Assistant Admin', $otherAdmin->name);
        $this->assertSame('assistant@example.com', $otherAdmin->email);
    }

    public function test_system_admin_can_update_other_admin_credentials(): void
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
            ->patch(route('admin.users.update', $otherAdmin), [
                '_token' => 'test-token',
                'name' => 'Manager Updated',
                'email' => 'manager-updated@example.com',
                'password' => 'newPassword123',
            ])
            ->assertRedirect();

        $otherAdmin->refresh();
        $this->assertSame('Manager Updated', $otherAdmin->name);
        $this->assertSame('manager-updated@example.com', $otherAdmin->email);
        $this->assertTrue(Hash::check('newPassword123', $otherAdmin->password));
    }
}
