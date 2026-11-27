
<h2>Confirm Your Password</h2>

@if ($errors->any())
    <div style="color:red">
        {{ $errors->first() }}
    </div>
@endif

<form method="POST" action="{{ url('/user/confirm-password') }}">
    @csrf

    <label>Current Password</label>

    <input
        type="password"
        name="password"
        required
        autocomplete="current-password"
    >

    <button type="submit">Confirm Password</button>
</form>
