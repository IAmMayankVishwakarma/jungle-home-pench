<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PragmaRX\Google2FA\Google2FA;

uses(RefreshDatabase::class);

it('confirms two-factor enrollment before requiring a code on later logins', function () {
    $user = User::factory()->create([
        'password' => 'correct-password',
    ]);
    $google2fa = app(Google2FA::class);

    $this->actingAs($user)
        ->get(route('two-factor.settings'))
        ->assertRedirect(route('password.confirm'));

    $this->get(route('password.confirm'))
        ->assertOk()
        ->assertSee('name="password"', false)
        ->assertSee("Verify it's you", false)
        ->assertSee('assets/css/auth.css');

    $this->post(route('password.confirm.store'), [
        'password' => 'correct-password',
    ])->assertRedirect(route('two-factor.settings'));

    $this->get(route('two-factor.settings'))
        ->assertOk()
        ->assertSee('Two-factor authentication is off')
        ->assertSee('action="'.route('two-factor.enable').'"', false)
        ->assertSee('assets/css/auth.css');

    $this->post(route('two-factor.enable'))->assertRedirect();

    $user->refresh();
    expect($user->two_factor_secret)->not->toBeNull()
        ->and($user->two_factor_confirmed_at)->toBeNull();

    $this->get(route('two-factor.settings'))
        ->assertOk()
        ->assertSee('Finish setting up your authenticator')
        ->assertSee('<svg', false)
        ->assertSee('name="code"', false);

    $secret = decrypt($user->two_factor_secret);
    $confirmationCode = $google2fa->oathTotp($secret, $google2fa->getTimestamp() - 1);

    $this->post(route('two-factor.confirm'), [
        'code' => $confirmationCode,
    ])->assertRedirect();

    expect($user->fresh()->two_factor_confirmed_at)->not->toBeNull();

    $this->get(route('two-factor.settings'))
        ->assertOk()
        ->assertSee('Two-factor authentication is on')
        ->assertSee('Recovery codes')
        ->assertSee('action="'.route('two-factor.disable').'"', false);

    $this->post(route('logout'));

    $this->post(route('login'), [
        'email' => $user->email,
        'password' => 'correct-password',
    ])->assertRedirect(route('two-factor.login'));

    $this->post(route('two-factor.login.store'), [
        'code' => $google2fa->getCurrentOtp($secret),
    ])->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);
});
