<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    public function test_login_page_renders_successfully(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('EndoCapture');
        $response->assertSee('Clinical Login');
    }

    public function test_active_user_can_authenticate_and_redirect_to_dashboard(): void
    {
        $user = User::where('email', 'admin@hospital.com')->first();
        if (!$user) {
            $user = User::factory()->create([
                'email' => 'admin_test@hospital.com',
                'password' => Hash::make('password'),
                'status' => 'Active',
            ]);
        }

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }
    public function test_inactive_user_cannot_authenticate(): void
    {
        $inactiveUser = User::firstOrCreate(
            ['email' => 'inactive@hospital.com'],
            [
                'name' => 'Inactive Staff',
                'password' => Hash::make('password'),
                'status' => 'Inactive',
            ]
        );

        $response = $this->post('/login', [
            'email' => $inactiveUser->email,
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_user_can_logout_safely(): void
    {
        $user = User::where('email', 'admin@hospital.com')->first();
        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    public function test_user_can_change_password(): void
    {
        $user = User::where('email', 'admin@hospital.com')->first();

        $response = $this->actingAs($user)->put('/profile/password', [
            'current_password' => 'password',
            'new_password' => 'NewSecurePass123!',
            'new_password_confirmation' => 'NewSecurePass123!',
        ]);

        $response->assertSessionHas('success');
        $this->assertTrue(Hash::check('NewSecurePass123!', $user->fresh()->password));

        // Revert password back
        $user->update(['password' => Hash::make('password')]);
    }
}
