<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return response()->json([
                'token' => $user->createToken('API Token')->plainTextToken
            ], 200);
        } catch(Exception $e) {
            \Log::error('Registration error: ' . $e->getMessage());

            return response()->json([
                'message' => 'Something went wrong, please try again.'
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'token' => 'Invalid credentials.'
                ], 401);
            }

            return response()->json([
                'token' => $user->createToken('API Token')->plainTextToken
            ], 200);
        } catch(Exception $e) {
            \Log::error('Login error: ' . $e->getMessage());

            return response()->json([
                'message' => 'Something went wrong, please try again.'
            ], 500);
        }
    }
}
