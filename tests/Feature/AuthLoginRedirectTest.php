<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthLoginRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_ignores_api_intended_url_and_redirects_to_dashboard(): void
    {
        $user = User::factory()->create([
            'username' => 'surat.portal',
            'password' => Hash::make('secret123'),
            'role' => 'instansi',
            'module_access' => ['surat'],
            'is_active' => true,
        ]);

        $response = $this->withSession([
            'url.intended' => '/api/balasan/unread-count',
        ])->post(route('login'), [
            'username' => $user->username,
            'password' => 'secret123',
            'login_source' => 'portal',
        ]);

        $response->assertRedirect(route('dashboard'));
    }

    public function test_login_keeps_non_api_intended_url(): void
    {
        $user = User::factory()->create([
            'username' => 'surat.tracking',
            'password' => Hash::make('secret123'),
            'role' => 'instansi',
            'module_access' => ['surat'],
            'is_active' => true,
        ]);

        $response = $this->withSession([
            'url.intended' => route('surat-masuk'),
        ])->post(route('login'), [
            'username' => $user->username,
            'password' => 'secret123',
            'login_source' => 'portal',
        ]);

        $response->assertRedirect(route('surat-masuk'));
    }

    public function test_login_accepts_email_for_legacy_accounts(): void
    {
        $user = User::factory()->create([
            'username' => 'surat.email',
            'email' => 'surat.email@example.test',
            'password' => Hash::make('secret123'),
            'role' => 'instansi',
            'module_access' => ['surat'],
            'is_active' => true,
        ]);

        $response = $this->post(route('login'), [
            'username' => $user->email,
            'password' => 'secret123',
            'login_source' => 'portal',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }
}
