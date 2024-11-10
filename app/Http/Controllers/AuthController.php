<?php

namespace App\Http\Controllers;

use App\Models\Parents;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $accountType = $request->accountType && in_array($request->accountType, ['student', 'teacher', 'parent']) ? $request->accountType : 'web';

        // Determine the provider based on the guard
//        $provider = config("auth.guards.{$accountType}.provider");

        // Determine the model based on the provider
//        $model = config("auth.providers.{$provider}.model");

//        $user = (new $model)->where('email', $request->email)
//            ->orWhere('infos->username' ,$request->username)->first();

        $attemptLogin = call_user_func([$this, $accountType], $request);

        if ($attemptLogin->status === false) {
            return response()->json([
                'message' => 'Invalid login details'
            ], 401);
        }

        $token = $attemptLogin->user->createToken('auth_token')->plainTextToken;
        $user = $attemptLogin->user;
        $user['accountType'] = $accountType;

        return response()->json([
            'token' => $token,
            'user' => $user
        ]);
    }

    public function student(Request $request): object
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);
        $user  = Student::where('infos->username', $request->username)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return (object)[
                'status' => false,
            ];
        }else{
            return (object)[
                'status' => true,
                'user' => $user
            ];
        }
    }

    public function web(Request $request): object
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return (object)[
                'status' => false,
            ];
        }else{
            return (object)[
                'status' => true,
                'user' => $user
            ];
        }
    }
    public function parent(Request $request): object
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $user = Parents::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return (object)[
                'status' => false,
            ];
        }else{
            return (object)[
                'status' => true,
                'user' => $user
            ];
        }
    }

    public function teacher(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $user = Teacher::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return (object)[
                'status' => false,
            ];
        }else{
            return (object)[
                'status' => true,
                'user' => $user
            ];
        }

    }


    public function logout(Request $request)
    {

        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out'
        ]);
    }

}
