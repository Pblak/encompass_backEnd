<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    // login function for user for spa
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if (!auth()->attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid login details'
            ], 401);
        }
        $user = auth()->user();
        $token = $user->createToken('token')->plainTextToken;
        return response()->json([
            'token' => $token,
            'user' => $user
        ]);
    }

}
