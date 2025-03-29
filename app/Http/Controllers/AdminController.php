<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{

    /**
     * Admin Login
     */
    public function login(Request $request): JsonResponse
    {
        // Validate login input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid login credentials',
                'errors' => $validator->errors()
            ], 422);
        }

        // Find admin user
        $user = User::where('email', $request->email)->where('role', 'admin')->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid admin credentials'], 401);
        }

        // Revoke previous tokens (optional security measure)
        $user->tokens()->delete();

        // Create a new access token for the admin
        $token = $user->createToken($request->device)->plainTextToken;

        return response()->json([
            'message' => 'Logged in successfully',
            'data' => [
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user,
            ]
        ]);
    }

    /**
     * Admin Logout
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Admin logged out successfully']);
    }
}
