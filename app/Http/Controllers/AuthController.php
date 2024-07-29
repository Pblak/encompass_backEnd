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
            'password' => 'required',
        ]);
        $accountType = $request->accountType &&
        in_array($request->accountType, ['student', 'teacher', 'parent']) ? $request->accountType : 'web';
        if (!auth($accountType)->attempt($request->only('email', 'password'))) {

            return response()->json([

                    'message' => 'Invalid login details'

            ], 401);
        }
        $user = auth($accountType)->user();
        $token = $user->createToken('token')->plainTextToken;
        $user['accountType'] = $accountType;
        return response()->json([
            'token' => $token,
            'user' => $user
        ]);
    }

}
