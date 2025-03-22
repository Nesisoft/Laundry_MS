<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Admin;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Verify Access Token
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function verifyProductKey(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'product_key' => 'required|string|min:16|max:16|exists:businesses,product_key',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Product key verification failed',
                    'data' => $validator->errors()
                ], 422);
            }

            return response()->json([
                'message' => 'Product key verified successfully',
                'data' => $validator->validated()
            ], 200);
        } catch (Exception $e) {
            Log::error('Error in login method: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred during login'
            ], 500);
        }
    }

    /**
     * Admin Login
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request): JsonResponse
    {
        try {
            // Validate login input
            $validator = Validator::make($request->all(), [
                'username' => 'required|username|exists:users,username',
                'password' => 'required|string',
                'device_name' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid login credentials',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Find admin user
            $user = User::where('username', $request->username)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid user credentials'
                ], 401);
            }

            // Create a new access token for the admin
            $device = $request->device_name ?? 'User Device';
            $token = $user->createToken($device)->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'Logged in successfully!',
                'data' => [
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    'user' => $user,
                ]
            ]);
        } catch (Exception $e) {
            Log::error('Error in login method: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred during login'
            ], 500);
        }
    }

    /**
     * Admin Logout
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'You have logged out successfully'
            ]);
        } catch (Exception $e) {
            Log::error('Error in logout method: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred during logout'
            ], 500);
        }
    }
}
