<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $accountType = $request->accountType && in_array($request->accountType, ['student', 'teacher', 'parent']) ? $request->accountType : 'web';

        // Determine the provider based on the guard
        $provider = config("auth.guards.{$accountType}.provider");

        // Determine the model based on the provider
        $model = config("auth.providers.{$provider}.model");

        // Attempt to fetch the user
        $user = (new $model)->where('email', $request->email)
            ->orWhere('infos->username' ,$request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid login details'
            ], 401);
        }

        $token = $user->createToken('token')->plainTextToken;
        $user['accountType'] = $accountType;

        return response()->json([
            'token' => $token,
            'user' => $user
        ]);
    }

    public function logout(Request $request)
    {

        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out'
        ]);
    }

}
