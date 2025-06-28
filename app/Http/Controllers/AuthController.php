<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            "email" => "required",
            "password" => "required"
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response([
                'status' => 400,
                'message' => 'invalid credentials'
            ], 400);
        }

        $token = $user->createToken($user->name)->plainTextToken;

        return response([
            'status' => 200,
            'message' => 'login succeed',
            'data' => $token
        ]);
    }

    public function register(Request $request)
    {
        $credentials = $request->validate([
            "name" => "required",
            "email" => "required|email|unique:users",
            "password" => "required|confirmed",
        ]);

        $optional_attributes = ['img_url', 'phone_number'];

        foreach ($optional_attributes as $optional) {
            if ($request[$optional]) {
                $credentials[$optional] = $request[$optional];
            }
        }

        $user = User::create($credentials)->fresh();

        return response([
            'status' => 201,
            'message' => 'register succeed'
        ], 201);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response([
            'status' => 200,
            'message' => 'logout succeed'
        ], 200);
    }
}
