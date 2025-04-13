<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Employee;
use App\Models\LocalConfig;
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

class AuthApiController extends Controller
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
                    'success' => false,
                    'message' => 'Product key verification failed',
                    'data' => $validator->errors()
                ], 422);
            }

            // Find admin user
            $product_key = LocalConfig::where('key', $request->product_key)->first();

            if ($product_key) {
                $admin = User::where('username', 'admin')->first();

                if (!$admin || !Hash::check('123@Password', $admin->password)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid admin credentials'
                    ], 401);
                }

                $token = $admin->createToken('Admin Access')->plainTextToken;

                return response()->json([
                    'success' => true,
                    'message' => 'Product key verified and registered successfully',
                    'data' => [
                        'token' => $token,
                    ]
                ], 201);
            }

            // Send a POST request to verify product key using Guzzle
            $client = new GuzzleClient();

            $response = $client->post('http://localhost/laundry_service/product-key', [
                'json' => [
                    'product_key' => $request->product_key
                ],
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                'timeout' => 10,  // Set a timeout
            ]);

            $result = json_decode($response->getBody(), true);

            if (!isset($result['success']) || !$result['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $result['message']
                ], 422);
            }

            DB::beginTransaction();

            // Ensure only update happens, not insert
            LocalConfig::where('key', 'product_key')->update(['value' => $request->product_key]);

            // Find admin user
            $admin = User::where('username', 'admin')->first();

            if (!$admin || !Hash::check('admin', $admin->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid admin credentials'
                ], 401);
            }

            $token = $admin->createToken('Admin Access')->plainTextToken;

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Product key verified and registered successfully',
                'data' => [
                    'token' => $token,
                ]
            ], 201);
        } catch (GuzzleRequestException $e) {
            DB::rollBack();
            Log::error('Error verifying product key: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to verify product key',
                'data' => null
            ], 500);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error in login method: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during login'
            ], 500);
        }
    }

    /**
     * Register a new admin with address
     */
    public function register(Request $request): JsonResponse
    {
        // Ensure the user is authenticated
        $authUser = Auth::user();
        if (!$authUser) {
            return response()->json(['message' => 'Unauthorized. Please log in.'], 401);
        }

        // Ensure the authenticated user is an admin
        if ($authUser->role !== 'admin') {
            return response()->json(['message' => 'Access denied. Only admins can add new admins.'], 403);
        }

        // Validate User Data & Address Data
        $validator = Validator::make($request->all(), [
            'email' => 'nullable|email|unique:users,email',
            'password' => 'required|string|min:6',
            'phone_number' => 'required|string|max:20',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'sex' => 'required|in:male,female',

            // Address fields are optional
            'address' => 'nullable|array',
            'address.street' => 'nullable|string|max:255',
            'address.city' => 'nullable|string|max:255',
            'address.state' => 'nullable|string|max:255',
            'address.zip_code' => 'nullable|string|max:20',
            'address.country' => 'nullable|string|max:100',
            'address.latitude' => 'nullable|numeric',
            'address.longitude' => 'nullable|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Create admin profile
        $employee = Employee::create([
            'phone_number' => $request->phone_number,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'sex' => $request->sex,
        ]);

        // Create address only if provided
        if ($request->has('address') && !empty($request->address)) {
            $address = new Address($request->address);
            $employee->address()->save($address); // Attaches polymorphic relationship
        }

        return response()->json([
            'message' => 'Admin registered successfully',
            'data' => $employee
        ], 201);
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
                'username' => 'required|string|exists:users,username',
                'password' => 'required|string',
                'device_name' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid login credentials',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Find admin user
            $user = User::where('username', $request->username)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid user credentials'
                ], 401);
            }

            // Create a new access token for the admin
            $device = $request->device_name ?? 'User Device';
            $token = $user->createToken($device)->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Logged in successfully!',
                'data' => [
                    'access_token' => $token,
                    'token_type' => 'Bearer'
                ]
            ]);
        } catch (Exception $e) {
            Log::error('Error in login method: ' . $e->getMessage());
            return response()->json([
                'success' => false,
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
                'success' => true,
                'message' => 'You have logged out successfully'
            ]);
        } catch (Exception $e) {
            Log::error('Error in logout method: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during logout'
            ], 500);
        }
    }
}
