<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Employee;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class EmployeeController
{
    public function fetchAll(Request $request) : JsonResponse {
        // Ensure the user is authenticated
        $authUser = Auth::user();

        if (!$authUser) {
            return response()->json(['message' => 'Unauthorized. Please log in.'], 401);
        }

        // Ensure the authenticated user is an admin
        if ($authUser->role !== 'manager') {
            return response()->json(['message' => 'Access denied. Only managers can add new users.'], 403);
        }

        try {
            //code...
        } catch (Exception $e) {
            Log::error('Error retrieving user data', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Error retrieving employees\' data',
                'succes' => false
            ], 500);
        }

    }

    public function fetchOne() : JsonResponse {
        return response()->json([
            'message' => '',
            'data' => []
        ], 201);
    }

    public function add(Request $request) : JsonResponse {
        // Ensure the user is authenticated
        $authUser = Auth::user();

        if (!$authUser) {
            return response()->json(['message' => 'Unauthorized. Please log in.'], 401);
        }

        // Ensure the authenticated user is an admin
        if ($authUser->role !== 'manager') {
            return response()->json(['message' => 'Access denied. Only managers can add new users.'], 403);
        }

        try {
            // Validate User Data & Address Data
            $validator = Validator::make($request->all(), [
                'email' => 'nullable|email|unique:users,email',
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
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

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

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'New employee added successfully',
                'data' => $employee
            ], 201);
        } catch (Exception $e) {
            DB::rollback();
            Log::error('Error sending verification code', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while adding new employee.'
            ], 500);
        }
    }

    public function delete() : JsonResponse {
        return response()->json([
            'message' => '',
            'data' => []
        ], 201);
    }

    public function update() : JsonResponse {
        return response()->json([
            'message' => '',
            'data' => []
        ], 201);
    }
}
