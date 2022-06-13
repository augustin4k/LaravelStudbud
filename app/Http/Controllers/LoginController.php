<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    public function submit(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'role' => 'simple_user'], $request->remember)) {

            $token = auth()->user()->createToken('authToken');
            // dd($token);
            if (auth()->user()->hasRole('admin') !== true)
                return redirect()->route('profile-timeline')->with('token', $token->plainTextToken);
            else
                return redirect()->route('admin-welcome')->with('token', $token->plainTextToken);
        }
        return redirect()->back()->withErrors(['user-no-exist' => 'Asemenea user nu exista!']);
    }
}
