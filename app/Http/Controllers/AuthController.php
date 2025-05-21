<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ✅ Register API
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'in:user,admin'
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['role'] = $data['role'] ?? 'user'; 

        $user = User::create($data);

        return response()->json([
            'status' => true,
            'message' => 'User created successfully',
            'user' => $user,
        ], 201);
    }

    // ✅ Login API
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user,
        ]);
    }

    // ✅ Profile API
    public function profile()
    {
        $user = auth()->user();

        return response()->json([
            'status' => true,
            'message' => 'User profile',
            'user' => $user,
        ]);
    }

    // ✅ Logout API
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => true,
            'message' => 'User logged out successfully',
        ]);
    }
}
