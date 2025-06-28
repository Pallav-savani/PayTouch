<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $user = User::create([
            'mobile' => $request->mobile,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        Log::info('User Registered Successfully', [
            'status' => 'Success',
            'message' => 'User Registered Successfully',
            'data' => $user
        ]);

        return response()->json([
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }


    public function login(Request $request)
    {
        $user = User::where('mobile', $request->mobile)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'mobile' => ['The provided credentials are incorrect.'],
            ]);
        }
        
        $token = $user->createToken('auth_token')->plainTextToken;
        
        Log::info('User Logged in Successfully', [
            'status' => 'Success',
            'message' => 'User logged in Successfully',
            'data' => $user
        ]);

        return response()->json([
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        Log::info('User Logged out Successfully', [
            'status' => 'Success',
            'message' => 'User Logout Successfully',
            'data' => $request->all()
        ]);

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

    public function user(Request $request)
    {
        
        return response()->json() ([
            'user' => $request->user(),
            'message' => 'User retrieved successfully'
        ]);
    }
}