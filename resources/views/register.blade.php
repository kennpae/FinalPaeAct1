@extends('layouts.auth')
@section('title', 'Register')

@section('content')
    <h2>Register</h2>

    <form method="POST" action="/register">
        @csrf
        <input type="text" name="name" placeholder="Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>

        <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
        <br>

        <button type="submit">Register</button>
    </form>

    <div class="link">
        Already have an account? <a href="/login">Login here</a>
    </div>
@endsection
