<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DefaultAdminBootstrapTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_creates_default_admin_when_no_users_exist(): void
    {
        $response = $this->withSession(['_token' => 'test-token'])->post('/login', [
            '_token' => 'test-token',
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/products');
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'admin@example.com',
            'is_admin' => true,
        ]);
        $this->assertTrue(User::where('email', 'admin@example.com')->exists());
    }
}
