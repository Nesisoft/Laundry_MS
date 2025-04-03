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
    public function fetchAll(): JsonResponse
    {
        $authUser = Auth::user();
        if (!$authUser || $authUser->role !== 'manager') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        try {
            $employees = Employee::all();
            return response()->json(['success' => true, 'data' => $employees], 200);
        } catch (Exception $e) {
            Log::error('Error retrieving employees', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Error retrieving employees', 'success' => false], 500);
        }
    }

    public function fetchArchivedEmployee(): JsonResponse
    {
        $authUser = Auth::user();
        if (!$authUser || $authUser->role !== 'manager') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        try {
            $employees = Employee::where('archived', true)->get();
            return response()->json(['success' => true, 'data' => $employees], 200);
        } catch (Exception $e) {
            Log::error('Error retrieving employees', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Error retrieving employees', 'success' => false], 500);
        }
    }

    public function fetchOne($id): JsonResponse
    {
        $authUser = Auth::user();
        if (!$authUser || $authUser->role !== 'manager') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        try {
            $employee = Employee::findOrFail($id);
            return response()->json(['success' => true, 'data' => $employee], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Employee not found', 'success' => false], 404);
        }
    }

    public function add(Request $request): JsonResponse
    {
        $authUser = Auth::user();
        if (!$authUser || $authUser->role !== 'manager') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|string|max:20',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'sex' => 'required|in:male,female',
            'role' => 'required|string|max:255',
            'salary' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();
            $employee = Employee::create($request->all());
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Employee added successfully', 'data' => $employee], 201);
        } catch (Exception $e) {
            DB::rollback();
            Log::error('Error adding employee', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Error adding employee'], 500);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        $authUser = Auth::user();
        if (!$authUser || $authUser->role !== 'manager') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        try {
            $employee = Employee::findOrFail($id);
            $employee->update($request->all());
            return response()->json(['success' => true, 'message' => 'Employee updated successfully', 'data' => $employee], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error updating employee', 'success' => false], 500);
        }
    }

    public function delete($id): JsonResponse
    {
        $authUser = Auth::user();
        if (!$authUser || $authUser->role !== 'manager') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        try {
            $employee = Employee::findOrFail($id);
            $employee->delete();
            return response()->json(['success' => true, 'message' => 'Employee deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error deleting employee', 'success' => false], 500);
        }
    }

    public function archive($id): JsonResponse
    {
        $authUser = Auth::user();
        if (!$authUser || $authUser->role !== 'manager') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        try {
            $employee = Employee::findOrFail($id);
            $employee->update(['archived' => true]);
            return response()->json(['success' => true, 'message' => 'Employee archived successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error archiving employee', 'success' => false], 500);
        }
    }
}
