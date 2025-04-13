<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserApiController extends Controller
{

    /**
     * Display a listing of the users.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): JsonResponse
    {
        $authUser = Auth::user();

        if (!$authUser) {
            return response()->json(['message' => 'Unauthorized. Please log in.'], 401);
        }

        try {
            $query = User::query();

            // Apply filters if provided
            if ($request->has('role')) {
                $query->where('role', $request->role);
            }

            if ($request->has('added_by')) {
                $query->where('added_by', $request->added_by);
            }

            if ($request->has('archived')) {
                $query->where('archived', $request->boolean('archived'));
            } else {
                $query->where('archived', false); // Default: show non-archived
            }

            // Pagination
            $perPage = $request->input('per_page', 15);
            $users = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $users
            ]);
        } catch (\Exception $e) {
            Log::error('Error in index method: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving users'
            ], 500);
        }
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): JsonResponse
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

        try {
            $validator = Validator::make($request->all(), [
                'employee' => 'required|string|exists:employees,id',
                'username' => 'required|string|unique:users,username',
                'password' => 'required|string|min:8',
                'role' => 'required|exists:roles,name',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            DB::beginTransaction();

            $user = new User();
            $user->employee_id = $request->employee;
            $user->username = $request->username;
            $user->password = Hash::make($request->password);
            $user->role = $request->role;
            $user->added_by = Auth::id(); // Using Auth facade instead of auth() helper
            $user->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'User created successfully',
                'data' => $user
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in store method: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating user'
            ], 500);
        }
    }

    /**
     * Display the specified user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): JsonResponse
    {
        $authUser = Auth::user();

        if (!$authUser) {
            return response()->json(['message' => 'Unauthorized. Please log in.'], 401);
        }

        try {
            $user = User::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $user
            ]);
        } catch (\Exception $e) {
            Log::error('Error in show method: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving user'
            ], 500);
        }
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id): JsonResponse
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

        try {
            DB::beginTransaction();
            $user = User::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'username' => 'sometimes|string|unique:users,username,' . $id,
                'password' => 'sometimes|string|min:8',
                'role' => 'sometimes|exists:roles,name',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            if ($request->has('username')) {
                $user->username = $request->username;
            }

            if ($request->has('password')) {
                $user->password = Hash::make($request->password);
            }

            if ($request->has('role')) {
                $user->role = $request->role;
            }

            $user->save();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
                'data' => $user
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in update method: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating user'
            ], 500);
        }
    }

    /**
     * Archive the specified user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function archive($id): JsonResponse
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

        try {
            DB::beginTransaction();
            $user = User::findOrFail($id);
            $user->archived = true;
            $user->save();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'User archived successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in archive method: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while archiving user'
            ], 500);
        }
    }

    /**
     * Restore the specified user from archive.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id): JsonResponse
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

        try {
            DB::beginTransaction();
            $user = User::findOrFail($id);
            $user->archived = false;
            $user->save();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'User restored successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in restore method: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while restoring user'
            ], 500);
        }
    }

    /**
     * Remove the specified user from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id): JsonResponse
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

        try {
            DB::beginTransaction();
            $user = User::findOrFail($id);
            $user->delete();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error in destroy method: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting user'
            ], 500);
        }
    }
}
