<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Verify Access Token
     */
    public function VerifyAccessToken(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'access_token' => 'required|string|min:16|max:16|exists:businesses,access_token',
        ]);

        if ($validator->fails()) {
            Log::info("Access token verification failed");
            return response()->json([
                'message' => 'Access token verification failed',
                'data' => $validator->errors()
            ], 422);
        }

        return response()->json([
            'message' => 'Access token verified successfully',
            'data' => $validator->validated()
        ], 200);
    }

    /**
     * Admin Login
     */
    public function login(Request $request, string $role): JsonResponse
    {
        return response()->json($request);
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
        $user = User::where('email', $request->email)->where('role', $role)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid admin credentials'], 401);
        }

        // Revoke previous tokens (optional security measure)
        $user->tokens()->delete();

        // Create a new access token for the admin
        $token = $user->createToken('admin-access-token')->plainTextToken;

        return response()->json([
            'message' => 'Logged in successfully',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
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
