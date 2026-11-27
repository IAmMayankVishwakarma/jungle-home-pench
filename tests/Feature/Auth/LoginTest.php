<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;
use PragmaRX\Google2FA\Google2FA;

uses(RefreshDatabase::class);

it('renders a login form that submits email and password to Fortify', function () {
    $this->get(route('login'))
        ->assertOk()
        ->assertSee('method="POST"', false)
        ->assertSee('action="'.route('login.store').'"', false)
        ->assertSee('name="email"', false)
        ->assertSee('name="password"', false)
        ->assertSee('name="remember"', false);
});

it('authenticates a user with valid email and password', function () {
    $user = User::factory()->create([
        'email' => 'guest@example.test',
        'password' => 'correct-password',
    ]);

    $this->post(route('login'), [
        'email' => $user->email,
        'password' => 'correct-password',
    ])->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);
});

it('authenticates a two-factor user with a recovery code after password login', function () {
    $user = User::factory()->create([
        'email' => 'two-factor@example.test',
        'password' => 'correct-password',
    ]);
    $recoveryCode = 'known-recovery-code';

    $user->forceFill([
        'two_factor_secret' => encrypt('JBSWY3DPEHPK3PXP'),
        'two_factor_recovery_codes' => encrypt(json_encode([$recoveryCode])),
        'two_factor_confirmed_at' => now(),
    ])->save();

    $this->post(route('login'), [
        'email' => $user->email,
        'password' => 'correct-password',
    ])->assertRedirect(route('two-factor.login'));

    $this->get(route('two-factor.login'))
        ->assertOk()
        ->assertSee('name="code"', false)
        ->assertSee('name="recovery_code"', false);

    $this->post(route('two-factor.login.store'), [
        'recovery_code' => $recoveryCode,
    ])->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);
    expect($user->fresh()->recoveryCodes())->not->toContain($recoveryCode);
});

it('authenticates a two-factor user with a valid authenticator code', function () {
    $user = User::factory()->create([
        'email' => 'authenticator@example.test',
        'password' => 'correct-password',
    ]);
    $secret = app(TwoFactorAuthenticationProvider::class)->generateSecretKey();
    $code = app(Google2FA::class)->getCurrentOtp($secret);

    $user->forceFill([
        'two_factor_secret' => encrypt($secret),
        'two_factor_recovery_codes' => encrypt(json_encode(['unused-recovery-code'])),
        'two_factor_confirmed_at' => now(),
    ])->save();

    $this->post(route('login'), [
        'email' => $user->email,
        'password' => 'correct-password',
    ])->assertRedirect(route('two-factor.login'));

    $this->post(route('two-factor.login.store'), [
        'code' => $code,
    ])->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);
});
