<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\Factory as SocialiteFactory;

class GoogleController extends Controller
{
    /**
     * Redirect user to Google.
     */
    public function redirect(SocialiteFactory $socialite)
    {
        return $socialite->driver('google')->redirect();
    }

    /**
     * Handle Google callback.
     */
    public function callback(
        SocialiteFactory $socialite
    ): RedirectResponse {

        $googleUser = $socialite->driver('google')->user();

        $googleData = $googleUser->getRaw();

        // Verify Google's email ownership claim.
        $emailVerified = (bool) (
            $googleData['email_verified']
            ?? $googleData['verified_email']
            ?? false
        );

        if (!$emailVerified) {
            return redirect()->route('login')->with(
                'error',
                'Your Google email address is not verified.'
            );
        }

        $email = Str::lower(trim($googleUser->getEmail()));

        if (!$email) {
            return redirect()->route('login')->with(
                'error',
                'Google did not provide an email address.'
            );
        }

        $providerId = (string) $googleUser->getId();

        // Check whether this Google account is already linked.
        $socialAccount = DB::table('social_accounts')
            ->where('provider', 'google')
            ->where('provider_id', $providerId)
            ->first();

        if ($socialAccount) {
            $user = User::findOrFail($socialAccount->user_id);

        } else {

            // Do not automatically link an existing email account.
            $existingUser = User::where('email', $email)->first();

            if ($existingUser) {
                return redirect()->route('login')->with(
                    'error',
                    'An account with this email already exists. Please log in using your existing account first.'
                );
            }

            // Create user and social account atomically.
            $user = DB::transaction(function () use (
                $googleUser,
                $email,
                $providerId
            ) {
                $newUser = User::create([
                    'name' => $googleUser->getName() ?: 'Google User',
                    'email' => $email,
                    'password' => Hash::make(Str::random(64)),
                ]);

                // Mark Google-verified email as verified.
                $newUser->forceFill([
                    'email_verified_at' => now(),
                ])->save();

                DB::table('social_accounts')->insert([
                    'user_id' => $newUser->id,
                    'provider' => 'google',
                    'provider_id' => $providerId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                return $newUser;
            });
        }

        // Regenerate session after authentication.
        Auth::login($user);

        request()->session()->regenerate();

        return redirect()->route('dashboard');
    }


/**
 * Start Google account linking for logged-in user.
 */
public function link(SocialiteFactory $socialite): RedirectResponse
{
    return $socialite->driver('google')
        ->redirectUrl(route('google.link.callback'))
        ->redirect();
}

/**
 * Complete Google account linking.
 */
public function linkCallback(
    SocialiteFactory $socialite
): RedirectResponse {

    $user = Auth::user();

    if (!$user) {
        return redirect()->route('login');
    }

    $googleUser = $socialite->driver('google')
        ->redirectUrl(route('google.link.callback'))
        ->user();

    $googleData = $googleUser->getRaw();

    $emailVerified = (bool) (
        $googleData['email_verified']
        ?? $googleData['verified_email']
        ?? false
    );

    if (!$emailVerified) {
        return redirect()->route('dashboard')
            ->with('error', 'Google email is not verified.');
    }

    $googleEmail = Str::lower(trim($googleUser->getEmail() ?? ''));

    // Only allow linking the same email as the logged-in account.
    if (
        !$googleEmail ||
        $googleEmail !== Str::lower(trim($user->email))
    ) {
        return redirect()->route('dashboard')
            ->with('error', 'Please use the same Google email as your account.');
    }

    $providerId = (string) $googleUser->getId();

    // Check whether this Google identity belongs to another user.
    $existingSocialAccount = DB::table('social_accounts')
        ->where('provider', 'google')
        ->where('provider_id', $providerId)
        ->first();

    if ($existingSocialAccount) {

        if ((int) $existingSocialAccount->user_id !== (int) $user->id) {
            return redirect()->route('dashboard')
                ->with('error', 'This Google account is already linked to another account.');
        }

        return redirect()->route('dashboard')
            ->with('status', 'Google account is already linked.');
    }

    // Check whether this user has linked another Google account.
    $userGoogleAccount = DB::table('social_accounts')
        ->where('user_id', $user->id)
        ->where('provider', 'google')
        ->first();

    if ($userGoogleAccount) {
        return redirect()->route('dashboard')
            ->with('error', 'Another Google account is already linked.');
    }

    DB::table('social_accounts')->insert([
        'user_id' => $user->id,
        'provider' => 'google',
        'provider_id' => $providerId,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return redirect()->route('dashboard')
        ->with('status', 'Google account linked successfully!');
}

}
