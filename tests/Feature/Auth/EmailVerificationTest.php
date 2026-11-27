<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;

uses(RefreshDatabase::class);

it('verifies an account from its signed email link without an active login', function () {
    $user = User::factory()->unverified()->create();
    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(30),
        [
            'id' => $user->getKey(),
            'hash' => sha1($user->getEmailForVerification()),
        ],
    );

    $this->get($verificationUrl)
        ->assertRedirect(route('login'))
        ->assertSessionHas('status', 'verification-link-verified');

    expect($user->fresh()->hasVerifiedEmail())->toBeTrue();

    $this->get(route('login'))
        ->assertOk()
        ->assertSee('Your email has been verified. You can now sign in.');
});

it('rejects a signed email link whose hash does not match the account', function () {
    $user = User::factory()->unverified()->create();
    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(30),
        [
            'id' => $user->getKey(),
            'hash' => sha1('another-account@example.test'),
        ],
    );

    $this->get($verificationUrl)->assertForbidden();

    expect($user->fresh()->hasVerifiedEmail())->toBeFalse();
});
