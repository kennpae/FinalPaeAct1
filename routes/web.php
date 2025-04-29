<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
Route::get('/', function () {
    return view('login');
});
Route::get('/register', function () {
    return view('register');
})->name('register');

Route::post('/register', function (Request $request) {
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8',
    ]);

    // Validate captcha first
    $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
        'secret' => env('RECAPTCHA_SECRET_KEY'),
        'response' => $request->input('g-recaptcha-response'),
        'remoteip' => $request->ip(),
    ]);

    if (!$response->json('success')) {
        return back()->with('error', 'Captcha verification failed.');
    }

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 'student', // default role
    ]);

    return redirect('/login')->with('success', 'Registered successfully! Please login.');
});



Route::get('/login', function () {
    return view('login');
})->name('login');



Route::post('/login', function (Request $request) {
    $credentials = $request->only('email', 'password');

    // Validate captcha first
    $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
        'secret' => env('RECAPTCHA_SECRET_KEY'),
        'response' => $request->input('g-recaptcha-response'),
        'remoteip' => $request->ip(),
    ]);

    if (!$response->json('success')) {
        return back()->with('error', 'Captcha verification failed.');
    }

    // Check if account is locked
    $user = User::where('email', $request->email)->first();
    if ($user && $user->is_locked) {
        return back()->with('error', 'Account is locked due to multiple failed login attempts.');
    }

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        // ✅ Reset failed attempts on successful login
        $user->update([
            'failed_attempts' => 0,
        ]);

        // ✅ Log Successful Login
        DB::table('login_logs')->insert([
            'email' => $request->email,
            'ip_address' => $request->ip(),
            'status' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if (Auth::user()->role === 'admin') {
            return redirect('/admin')->with('success', 'Welcome back, Admin!');
        } else {
            return redirect('/dashboard')->with('success', 'Welcome back!');
        }
    }

    // ✅ Handle failed login
    if ($user) {
        $user->increment('failed_attempts');

        if ($user->failed_attempts >= 3) {
            $user->update([
                'is_locked' => true,
            ]);
        }
    }

    // ✅ Log Failed Login
    DB::table('login_logs')->insert([
        'email' => $request->email,
        'ip_address' => $request->ip(),
        'status' => false,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return back()->with('error', 'Invalid credentials');
})->middleware('throttle:5,1');



Route::get('/dashboard', function (Request $request) {
    return view('dashboard')->with('success', session('success'));
})->middleware('auth');


Route::get('/admin', function () {
    if (auth()->user()->role !== 'admin') {
        abort(403);
    }

    $users = User::all();
    $logs = DB::table('login_logs')->latest()->get();

    return view('admin', compact('users', 'logs'))->with('success', session('success'));
})->middleware('auth');

Route::post('/admin/unlock/{id}', function ($id) {
    $user = User::findOrFail($id);
    $user->update([
        'is_locked' => false,
        'failed_attempts' => 0,
    ]);

    return redirect('/admin')->with('success', 'User unlocked successfully!');
})->middleware('auth');


Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout');
