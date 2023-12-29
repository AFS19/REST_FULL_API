<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    # Register method
    public function register(Request $request)
    {
        # data validation
        $request->validate([
            "name" => 'required|string|max:255',
            "email" => 'required|string|email|max:255|unique:users',
            "password" => 'required|string|min:8|confirmed',
        ]);

        try {
            # data save & create new user
            User::create([
                "name" => $request->name,
                "email" => $request->email,
                "password" => bcrypt($request->password),
            ]);

            # return success response
            return response()->json([
                "status" => "success",
                "message" => "user created successfully"
            ]);
        } catch (\Throwable $th) {

            return response()->json([
                "status" => "failed",
                "error" => $th->getMessage(),
            ], 500);
        }
    }

    # Login method
    public function login(Request $request)
    {
        # data validation
        $request->validate([
            "email" => "required|string|email|max:255",
            "password" => "required|string|min:8|",
        ]);

        # jwt auth and attempt
        $credentials = $request->only('email', 'password');
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        #response
        return $this->responseWithToken($token, $credentials);
    }

    # Profile method
    public function profile()
    {
        $user = auth()->user();
        $credentials = [
            "name" => $user?->name,
            "email" => $user?->email,
        ];
        return response()->json([
            "status" => "success",
            "message" => "Profile data",
            "user" => $credentials,
        ]);
    }

    # Refresh token method
    public function refreshToken()
    {
    }

    # Logout method
    public function logout()
    {
    }

    # method return response with token
    protected function responseWithToken($token, $user)
    {
        return response()->json([
            "message" => "logged in successfully",
            "user" => $user,
            "access_token" => $token,
            "token_type" => 'bearer',
        ]);
    }
}
