
<!DOCTYPE html>
<html>
<head>
    <title>Verify Email</title>
</head>
<body>

    <h2>Verify Your Email Address</h2>

    <p>
        Please verify your email address by clicking
        the link sent to your registered email.
    </p>

    @if (session('status') == 'verification-link-sent')
        <p style="color: green;">
            A new verification link has been sent to your email.
        </p>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit">Resend Verification Email</button>
    </form>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>

</body>
</html>
