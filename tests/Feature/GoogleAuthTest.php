<?php

namespace Tests\Feature;

use App\Models\User;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;
use Tests\TestCase;

class GoogleAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_redirects_to_login_when_google_not_configured(): void
    {
        config([
            'services.google.client_id' => null,
            'services.google.client_secret' => null,
        ]);

        $response = $this->get(route('auth.google'));

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors(['login']);
    }

    public function test_redirects_to_google_when_configured(): void
    {
        config([
            'services.google.client_id' => 'dummy-client-id',
            'services.google.client_secret' => 'dummy-secret',
            'services.google.redirect' => 'http://127.0.0.1:8000/auth/google/callback',
        ]);

        $response = $this->get(route('auth.google'));

        $response->assertRedirect();
        $this->assertStringContainsString('accounts.google.com', $response->headers->get('Location'));
    }

    public function test_google_callback_creates_new_user_and_authenticates(): void
    {
        $mockSocialiteUser = Mockery::mock(SocialiteUser::class);
        $mockSocialiteUser->shouldReceive('getId')->andReturn('google-123456');
        $mockSocialiteUser->shouldReceive('getName')->andReturn('Google Tester');
        $mockSocialiteUser->shouldReceive('getNickname')->andReturn('googletester');
        $mockSocialiteUser->shouldReceive('getEmail')->andReturn('googletester@example.com');
        $mockSocialiteUser->shouldReceive('getAvatar')->andReturn('https://lh3.googleusercontent.com/test-avatar');

        $provider = Mockery::mock('Laravel\Socialite\Two\GoogleProvider');
        $provider->shouldReceive('user')->andReturn($mockSocialiteUser);

        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        $response = $this->get(route('auth.google.callback'));

        $response->assertRedirect('/');
        $this->assertAuthenticated();

        $user = auth()->user();
        $this->assertEquals('google-123456', $user->google_id);
        $this->assertEquals('customers', $user->role);
        $this->assertEquals('googletester@example.com', $user->email);
    }

    public function test_google_callback_links_existing_user_by_email(): void
    {
        $existingUser = User::create([
            'nama_lengkap' => 'Existing Customer',
            'username'     => 'existinguser',
            'email'        => 'existing@example.com',
            'no_telp'      => '081234567890',
            'role'         => 'customers',
        ]);

        $mockSocialiteUser = Mockery::mock(SocialiteUser::class);
        $mockSocialiteUser->shouldReceive('getId')->andReturn('google-987654');
        $mockSocialiteUser->shouldReceive('getName')->andReturn('Existing Customer');
        $mockSocialiteUser->shouldReceive('getNickname')->andReturn('existing');
        $mockSocialiteUser->shouldReceive('getEmail')->andReturn('existing@example.com');
        $mockSocialiteUser->shouldReceive('getAvatar')->andReturn('https://lh3.googleusercontent.com/existing-avatar');

        $provider = Mockery::mock('Laravel\Socialite\Two\GoogleProvider');
        $provider->shouldReceive('user')->andReturn($mockSocialiteUser);

        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        $response = $this->get(route('auth.google.callback'));

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($existingUser);

        $existingUser->refresh();
        $this->assertEquals('google-987654', $existingUser->google_id);
    }

    public function test_google_callback_handles_exception_gracefully(): void
    {
        $provider = Mockery::mock('Laravel\Socialite\Two\GoogleProvider');
        $provider->shouldReceive('user')->andThrow(new Exception('Access denied by user'));

        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        $response = $this->get(route('auth.google.callback'));

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors(['login']);
        $this->assertGuest();
    }
}
