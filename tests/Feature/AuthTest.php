<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_a_user_can_log_in_with_correct_credentials(): void
    {
        User::factory()->create([
            'email' => 'officer@strathmore.edu',
            'password' => bcrypt('secret123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'officer@strathmore.edu',
            'password' => 'secret123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    public function test_login_fails_with_wrong_password(): void
    {
        User::factory()->create([
            'email' => 'officer@strathmore.edu',
            'password' => bcrypt('secret123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'officer@strathmore.edu',
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }
}
