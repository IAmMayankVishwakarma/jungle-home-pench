<h2>User Login</h2>

@if ($errors->any())
    @foreach ($errors->all() as $error)
        <p style="color:red">{{ $error }}</p>
    @endforeach
@endif

<form method="POST" action="{{ route('login') }}">
    @csrf

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

    <label>
        <input type="checkbox" name="remember">
        Remember me
    </label>

    <button type="submit">Login</button>
</form>

<a href="{{ route('password.request') }}">
    Forgot Password?
</a>

<p>
    Don't have an account?
    <a class="btn btn-primary" href="{{ route('register') }}">Sign up</a>
</p>