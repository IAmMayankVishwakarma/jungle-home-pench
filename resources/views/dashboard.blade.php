<h1>Welcome, {{ auth()->user()->name }}</h1>

<p>{{ auth()->user()->email }}</p>

<form method="POST" action="{{ route('logout') }}">
    @csrf

    <button type="submit">Logout</button>
</form>

@if (session('status'))
    <p style="color: green;">
        {{ session('status') }}
    </p>
@endif

@if (session('error'))
    <p style="color: red;">
        {{ session('error') }}
    </p>
@endif

<a href="{{ route('google.link') }}">
    Connect Google Account
</a>