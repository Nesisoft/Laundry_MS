<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Employee;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class EmployeeApiController extends Controller
{
    public function fetchAll(Request $request): JsonResponse
    {
        $authUser = Auth::user();

        if (!$authUser) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Please log in.'
            ], 401);
        }

        try {
            $query = Employee::query();

            // Apply filters if provided
            if ($request->has('role')) {
                $query->where('role', $request->role);
            }

            if ($request->has('sex')) {
                $query->where('sex', $request->sex);
            }

            if ($request->has('archived')) {
                $query->where('archived', $request->boolean('archived'));
            } else {
                $query->where('archived', false); // Default: show non-archived
            }

            // Pagination
            $perPage = $request->input('per_page', 15);
            $employees = $query->paginate($perPage);

            return response()->json(['success' => true, 'data' => $employees], 200);
        } catch (Exception $e) {
            Log::error('Error retrieving employees', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Error retrieving employees', 'success' => false], 500);
        }
    }

    public function fetchOne($id): JsonResponse
    {
        $authUser = Auth::user();

        if (!$authUser) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Please log in.'
            ], 401);
        }

        try {
            $employee = Employee::findOrFail($id);
            return response()->json(['success' => true, 'data' => $employee], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Employee not found',
                'success' => false
            ], 404);
        }
    }

    public function add(Request $request): JsonResponse
    {
        $authUser = Auth::user();

        if (!$authUser) {
            return response()->json(['message' => 'Unauthorized. Please log in.'], 401);
        }

        // Ensure the authenticated user is an admin
        if (!in_array($authUser->role, ['admin', 'manager'])) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|string|max:20',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'sex' => 'required|in:male,female',
            'role' => 'required|string|max:255',
            'salary' => 'nullable|numeric',

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

        try {
            DB::beginTransaction();
            $employee = Employee::create($request->all());

            // Create address only if provided
            if ($request->has('address') && !empty($request->address)) {
                $address = new Address($request->address);
                $employee->address()->save($address); // Attaches polymorphic relationship
            }
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Employee added successfully',
                'data' => $employee
            ], 201);
        } catch (Exception $e) {
            DB::rollback();
            Log::error('Error adding employee', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error adding employee'
            ], 500);
        }
    }

    public function addAddress(Request $request, $id): JsonResponse
    {
        $authUser = Auth::user();

        if (!$authUser) {
            return response()->json(['message' => 'Unauthorized. Please log in.'], 401);
        }

        if (!in_array($authUser->role, ['admin', 'manager'])) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'street' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $employee = Employee::findOrFail($id);

            // Check if employee already has address
            if ($employee->address) {
                return response()->json([
                    'success' => false,
                    'message' => 'Address already exists. Please update instead.'
                ], 409);
            }

            $address = new Address($request->all());
            $employee->address()->save($address);

            return response()->json([
                'success' => true,
                'message' => 'Address added successfully',
                'data' => $address
            ]);
        } catch (Exception $e) {
            Log::error('Error adding address', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error adding address'
            ], 500);
        }
    }

    public function updateAddress(Request $request, $id): JsonResponse
    {
        $authUser = Auth::user();

        if (!$authUser) {
            return response()->json(['message' => 'Unauthorized. Please log in.'], 401);
        }

        if (!in_array($authUser->role, ['admin', 'manager'])) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'street' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $employee = Employee::findOrFail($id);

            if (!$employee->address) {
                return response()->json([
                    'success' => false,
                    'message' => 'Address not found for this employee.'
                ], 404);
            }

            $employee->address->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Address updated successfully',
                'data' => $employee->address
            ]);
        } catch (Exception $e) {
            Log::error('Error updating address', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error updating address'
            ], 500);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        $authUser = Auth::user();

        if (!$authUser) {
            return response()->json(['message' => 'Unauthorized. Please log in.'], 401);
        }

        // Ensure the authenticated user is an admin
        if (!in_array($authUser->role, ['admin', 'manager'])) {
            return response()->json(['message' => 'Access denied.'], 403);
        }

        try {
            DB::beginTransaction();
            $employee = Employee::findOrFail($id);
            $employee->update($request->all());
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Employee updated successfully', 'data' => $employee], 200);
        } catch (Exception $e) {
            DB::rollback();
            Log::error('Error adding employee', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Error updating employee', 'success' => false], 500);
        }
    }

    public function delete($id): JsonResponse
    {
        $authUser = Auth::user();

        if (!$authUser) {
            return response()->json(['message' => 'Unauthorized. Please log in.'], 401);
        }

        // Ensure the authenticated user is an admin
        if (!in_array($authUser->role, ['admin', 'manager'])) {
            return response()->json(['message' => 'Access denied.'], 403);
        }

        try {
            DB::beginTransaction();
            $employee = Employee::findOrFail($id);
            $employee->delete();
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Employee deleted successfully'], 200);
        } catch (Exception $e) {
            DB::rollback();
            Log::error('Error adding employee', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Error deleting employee',
                'success' => false
            ], 500);
        }
    }

    public function archive($id): JsonResponse
    {
        $authUser = Auth::user();

        if (!$authUser) {
            return response()->json(['message' => 'Unauthorized. Please log in.'], 401);
        }

        // Ensure the authenticated user is an admin
        if (!in_array($authUser->role, ['admin', 'manager'])) {
            return response()->json(['message' => 'Access denied.'], 403);
        }

        try {
            DB::beginTransaction();
            $employee = Employee::findOrFail($id);
            $employee->update(['archived' => true]);
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Employee archived successfully'], 200);
        } catch (Exception $e) {
            DB::rollback();
            Log::error('Error adding employee', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Error archiving employee', 'success' => false], 500);
        }
    }
}
