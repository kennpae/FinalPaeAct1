@extends('layouts.auth')
@section('title', 'Login')

@section('content')
    <h2>Login</h2>

    <form method="POST" action="/login">
        @csrf
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
<br>
        <button type="submit">Login</button>
    </form>

    <div class="link">
        Don't have an account? <a href="/register">Register here</a>
    </div>
@endsection
