
<h2>Two-Factor Authentication Settings</h2>

@if (session('status'))
    <div style="color:green">
        {{ session('status') }}
    </div>
@endif

@php
    $user = auth()->user();
@endphp

@if (!$user->two_factor_secret)

    <p>Two-factor authentication is currently disabled.</p>

    <form method="POST" action="{{ url('/user/two-factor-authentication') }}">
        @csrf
        <button type="submit">Enable 2FA</button>
    </form>

@elseif (!$user->two_factor_confirmed_at)

    <h3>Scan QR Code</h3>

    <p>Scan this QR code using Google Authenticator.</p>

    <div>
        {!! $user->twoFactorQrCodeSvg() !!}
    </div>

    <h3>Confirm your authenticator</h3>

    <form method="POST" action="{{ url('/user/confirmed-two-factor-authentication') }}">
        @csrf

        <input
            type="text"
            name="code"
            placeholder="Enter 6-digit OTP"
            inputmode="numeric"
            maxlength="6"
            required
        >

        <button type="submit">Confirm 2FA</button>
    </form>

@else

    <p style="color:green">
        Two-factor authentication is enabled.
    </p>

    <h3>Recovery Codes</h3>

    <p>Store these codes somewhere safe.</p>

    @foreach ($user->recoveryCodes() as $code)
        <div>{{ $code }}</div>
    @endforeach

    <form method="POST" action="{{ url('/user/two-factor-recovery-codes') }}">
        @csrf
        <button type="submit">Regenerate Recovery Codes</button>
    </form>

    <form method="POST" action="{{ url('/user/two-factor-authentication') }}">
        @csrf
        @method('DELETE')

        <button type="submit">Disable 2FA</button>
    </form>

@endif
