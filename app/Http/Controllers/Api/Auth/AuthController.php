<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

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



        #
    }

    # Login method
    public function login()
    {
    }

    # Profile method
    public function profile()
    {
    }

    # Refresh token method
    public function refreshToken()
    {
    }

    # Logout method
    public function logout()
    {
    }
}
