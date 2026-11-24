<h2>Reset Password</h2>

@if ($errors->any())
    @foreach ($errors->all() as $error)
        <p style="color:red">{{ $error }}</p>
    @endforeach
@endif

<form method="POST" action="{{ route('password.update') }}">
    @csrf

    <input type="hidden" name="token" value="{{ $request->route('token') }}">

    <input
        type="email"
        name="email"
        value="{{ old('email', $request->email) }}"
        placeholder="Email"
        required
    >

    <input
        type="password"
        name="password"
        placeholder="New password"
        required
    >

    <input
        type="password"
        name="password_confirmation"
        placeholder="Confirm new password"
        required
    >

    <button type="submit">Update Password</button>
</form>