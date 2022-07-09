<?php

namespace App\Mail;

// use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\DataCollector\RequestDataCollector;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\User;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// MIDDLEWARE-URI
{
    // for non authentificated clients view
    Route::middleware('guest')->group(function () {
        Route::view('/', 'pages/non-authentificated/login')->name('index');
        Route::view('/home', 'pages/non-authentificated/login')->name('home');
        Route::view('/login', 'pages/non-authentificated/login')->name('login');
        Route::view('/register', 'pages/non-authentificated/register')->name('register');
        Route::view('/reset', 'pages/non-authentificated/reset')->name('password.request');
    });
    // vor clients with verified or active account
    Route::middleware('verified')->group(function () {
        Route::get('/chat', function (Request $req) {
            if (!empty($req->id)) {
                $user_exist = User::where('id', $req->id)
                    ->limit(1)
                    ->get()
                    ->toArray();
                if ($req->id == auth()->user()->id || empty($user_exist))
                    return redirect()->back();
            }
            return view('pages/authentificated/chat');
        })->name('chat');
        Route::get('/search', "App\Http\Controllers\Controller@search_people")->name("search-people");
        Route::view('/feed', 'pages/authentificated/feed')->name('profile-feed');
        Route::view('/settings', 'pages/authentificated/settings')->name('profile-settings');
        Route::post('/settings/update', "App\Http\Controllers\RegisterController@update_settings")->name("user-update-settings");
        // for clients that are admins
        Route::middleware('admin')->group(function () {
            Route::view('/admin-users', 'pages/administrator/users')->name('admin-users');
            Route::view('/admin-notifications', 'pages/administrator/notifications')->name('admin-notifications');
            Route::view('/admin-panel', 'pages/administrator/welcome')->name('admin-welcome');
        });
    });

    // WITHOUT MIDDLEWARE
    Route::get('/timeline', "App\Http\Controllers\Controller@timeline")->name("profile-timeline");
    Route::view('/reviews', 'pages/authentificated/reviews')->name('profile-reviews');
    Route::get('/friends', "App\Http\Controllers\Controller@friends")->name("profile-friends");
    Route::view('/files', 'pages/authentificated/files')->name('profile-files');
    // return view
    Route::view('/about', 'pages/non-authentificated/about')->name('about');
    Route::view('/contact', 'pages/non-authentificated/contact')->name('contact');
    // pentru submit-uri
    Route::post('/contact/submit', "App\Http\Controllers\Controller@ContactSubmit")->name("contact-submit");
    Route::post('/register/submit', "App\Http\Controllers\RegisterController@submit")->name("user-register");
    Route::post('/login/submit', "App\Http\Controllers\LoginController@submit")->name("user-login");
    // logout
    Route::get('/logout', function () {
        Auth::logout();
        return redirect('/');
    })->name('logout');
}

// stock routes
{
    // pentru confirm email
    Route::get('/email/verify', function () {
        return view('auth.verify');
    })->middleware('auth')->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        Auth::logout();
        return redirect()->route('login')->with('message-about-authentification', 'Ti-ai confirmat email-ul, acum te poti loga!');
    })->middleware(['auth', 'signed'])->name('verification.verify');
    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('resent', 'Verification link sent!');
    })->middleware(['auth', 'throttle:6,1'])->name('verification.resend');
    // pentru reset password
    Route::post('/forgot-password', function (Request $request) {
        $request->validate(['email' => 'required|email']);
        $status = Password::sendResetLink(
            $request->only('email')
        );
        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    })->middleware('guest')->name('password.email');
    Route::get('/reset-password/{token}', function ($token) {
        return view('reset', ['token' => $token]);
    })->middleware('guest')->name('password.reset');
    Route::post('/reset-password', function (Request $request) {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:4|max:12|confirmed',
        ]);
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );
        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('message-about-authentification', 'Ti s-a resetat parola cu succes')
            : back()->withErrors(['email' => [__($status)]]);
    })->middleware('guest')->name('password.update');
}

// pentru vue js
Route::get('/{any}', function () {
    return redirect()->back();
})->where("any", '.*');
