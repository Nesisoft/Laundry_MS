<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Admin;
use App\Models\Business;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException as GuzzleRequestException;
use Illuminate\Support\Facades\DB;

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
                'product_key' => 'required|string|min:16|max:16',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Product key verification failed',
                    'data' => $validator->errors()
                ], 422);
            }

            // Find admin user
            $product_key = Business::where('key', $request->product_key)->first();

            if ($product_key) {
                $admin = User::where('username', 'admin')->first();

                if (!$admin || !Hash::check('123@Password', $admin->password)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Invalid admin credentials'
                    ], 401);
                }

                $token = $admin->createToken('Admin Access')->plainTextToken;

                return response()->json([
                    'status' => 'success',
                    'message' => 'Product key verified and registered successfully',
                    'data' => [
                        'token' => $token,
                    ]
                ], 201);
            }

            // Send a POST request to verify product key using Guzzle
            $client = new GuzzleClient();

            $response = $client->post('http://localhost/auth/business/product-key', [
                'json' => [
                    'product_key' => $request->product_key
                ]
            ]);

            $result = json_decode($response->getBody(), true);

            if (!isset($result['data']) || empty($result['data'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid product key'
                ], 422);
            }

            if (isset($result['data']) && !empty($result['data']) && !$result['data']['allow_access']) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You have exceeded the number of installation allowed for this product key'
                ], 422);
            }

            DB::beginTransaction();

            Business::updateOrCreate([
                'key' => 'product_key',
                'value' => $request->product_key
            ]);

            // Find admin user
            $admin = User::where('username', 'admin')->first();

            if (!$admin || !Hash::check('123@Password', $admin->password)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid admin credentials'
                ], 401);
            }

            $token = $admin->createToken('Admin Access')->plainTextToken;

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Product key verified and registered successfully',
                'data' => [
                    'token' => $token,
                ]
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error in login method: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred during login'
            ], 500);
        } catch (GuzzleRequestException $e) {
            DB::rollBack();
            Log::error('Error verifying product key: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to verify product key',
                'data' => null
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
