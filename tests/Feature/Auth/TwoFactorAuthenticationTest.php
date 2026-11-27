<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PragmaRX\Google2FA\Google2FA;

uses(RefreshDatabase::class);

it('confirms two-factor enrollment before requiring a code on later logins', function () {
    $user = User::factory()->create([
        'password' => 'correct-password',
    ]);

    $this->actingAs($user)
        ->get(route('two-factor.settings'))
        ->assertRedirect(route('password.confirm'));

    $this->get(route('password.confirm'))
        ->assertOk()
        ->assertSee('name="password"', false);

    $this->post(route('password.confirm.store'), [
        'password' => 'correct-password',
    ])->assertRedirect(route('two-factor.settings'));

    $this->get(route('two-factor.settings'))->assertOk();

    $this->post(route('two-factor.enable'))->assertRedirect();

    $user->refresh();
    expect($user->two_factor_secret)->not->toBeNull()
        ->and($user->two_factor_confirmed_at)->toBeNull();

    $secret = decrypt($user->two_factor_secret);
    $code = app(Google2FA::class)->getCurrentOtp($secret);

    $this->post(route('two-factor.confirm'), [
        'code' => $code,
    ])->assertRedirect();

    expect($user->fresh()->two_factor_confirmed_at)->not->toBeNull();

    $this->post(route('logout'));

    $this->post(route('login'), [
        'email' => $user->email,
        'password' => 'correct-password',
    ])->assertRedirect(route('two-factor.login'));

    $this->post(route('two-factor.login.store'), [
        'code' => app(Google2FA::class)->getCurrentOtp($secret),
    ])->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);
});
