<h2>Create Account</h2>

@if ($errors->any())
    @foreach ($errors->all() as $error)
        <p style="color:red">{{ $error }}</p>
    @endforeach
@endif

<form method="POST" action="{{ route('register') }}">
    @csrf

    <input
        type="text"
        name="name"
        placeholder="Full name"
        required
    >

    <input
        type="email"
        name="email"
        placeholder="Email address"
        required
    >

    <input
        type="password"
        name="password"
        placeholder="Password"
        required
    >

    <input
        type="password"
        name="password_confirmation"
        placeholder="Confirm password"
        required
    >

    <button type="submit">Create Account</button>
</form>

<a href="{{ route('login') }}">Already registered? Login</a>