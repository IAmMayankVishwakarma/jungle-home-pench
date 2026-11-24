<h2>Forgot Password</h2>

@if (session('status'))
    <p style="color:green">{{ session('status') }}</p>
@endif

@if ($errors->any())
    <p style="color:red">{{ $errors->first() }}</p>
@endif

<form method="POST" action="{{ route('password.email') }}">
    @csrf

    <input
        type="email"
        name="email"
        placeholder="Enter registered email"
        required
    >

    <button type="submit">Send Reset Link</button>
</form>

<a href="{{ route('login') }}">Back to Login</a>