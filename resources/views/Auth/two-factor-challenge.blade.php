
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Two Factor Authentication</title>
</head>
<body>

    <h2>Two-Factor Authentication</h2>

    <p>Enter the 6-digit code from your authenticator app.</p>

    @if ($errors->any())
        <div style="color:red">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ url('/two-factor-challenge') }}">
        @csrf

        <label>Authentication Code</label>

        <input
            type="text"
            name="code"
            inputmode="numeric"
            autocomplete="one-time-code"
            maxlength="6"
            required
        >

        <button type="submit">Verify Code</button>
    </form>

    <hr>

    <p>Lost access to your authenticator?</p>

    <form method="POST" action="{{ url('/two-factor-challenge') }}">
        @csrf

        <label>Recovery Code</label>

        <input
            type="text"
            name="recovery_code"
            required
        >

        <button type="submit">Use Recovery Code</button>
    </form>

</body>
</html>
