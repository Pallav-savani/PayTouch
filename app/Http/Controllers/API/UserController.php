<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use \Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user(); // or Auth::user()
        
        return response()->json([
            'id' => $user->id,
            'mobile' => $user->mobile,
            'email' => $user->email,
            'wallet_balance' => $user->wallet_balance ?? 0,
            'kyc_completed' => $user->kyc_completed,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'mobile' => 'required|string|max:12|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'mobile' => $request->mobile,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(),
        ]);

        Log::info('User Registered Successfully', [
            'status' => $request->all(),
            'message' => 'User Registered Successfully',
            'data' => $user
        ]);

        return response()->json([
            'data' => $user,
            'message' => 'User created successfully'
        ], 201);
    }

    public function show(User $user)
    {
        return response()->json()([
            'data' => $user,
            'message' => 'User retrieved successfully'
        ]);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'mobile' => ['sometimes', 'required', 'string', 'max:12', Rule::unique('users')->ignore($user->id)], // Fixed: Added mobile to validation and unique rule
            'email' => ['sometimes', 'required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'sometimes|required|string|min:8',
        ]);

        $updateData = $request->only(['mobile', 'email']);
        
        if ($request->has('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return response()->json([
            'data' => $user,
            'message' => 'User updated successfully'
        ], 201);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully'
        ]);
    }

    public function user(Request $request)
    {
        return response()->json($request->user()); // returns the logged-in user
    }

}