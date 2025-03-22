<?php

namespace App\Http\Controllers;

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

class UserController extends Controller
{
    /**
     * Constructor to apply middleware
     */
    // public function __construct()
    // {
    //     // Apply auth middleware to all methods except login and register
    //     $this->middleware('auth:sanctum')->except(['login', 'register']);
    // }

    /**
     * Display a listing of the users.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = User::query();

            // Apply filters if provided
            if ($request->has('role')) {
                $query->where('role', $request->role);
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
                'status' => 'success',
                'data' => $users
            ]);
        } catch (\Exception $e) {
            Log::error('Error in index method: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
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
        try {
            $validator = Validator::make($request->all(), [
                'username' => 'required|string|unique:users,username',
                'password' => 'required|string|min:8',
                'role' => 'required|in:admin,manager,employee',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            DB::beginTransaction();

            $user = new User();
            $user->username = $request->username;
            $user->password = Hash::make($request->password);
            $user->role = $request->role;
            $user->added_by = Auth::id(); // Using Auth facade instead of auth() helper
            $user->save();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'User created successfully',
                'data' => $user
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in store method: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
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
        try {
            $user = User::findOrFail($id);

            return response()->json([
                'status' => 'success',
                'data' => $user
            ]);
        } catch (\Exception $e) {
            Log::error('Error in show method: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
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
        try {
            DB::beginTransaction();
            $user = User::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'username' => 'sometimes|string|unique:users,username,' . $id,
                'password' => 'sometimes|string|min:8',
                'role' => 'sometimes|in:admin,manager,employee',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
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
                'status' => 'success',
                'message' => 'User updated successfully',
                'data' => $user
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in update method: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
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
        try {
            DB::beginTransaction();
            $user = User::findOrFail($id);
            $user->archived = true;
            $user->save();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'User archived successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in archive method: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
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
        try {
            DB::beginTransaction();
            $user = User::findOrFail($id);
            $user->archived = false;
            $user->save();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'User restored successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in restore method: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
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
        try {
            DB::beginTransaction();
            $user = User::findOrFail($id);
            $user->delete();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'User deleted successfully'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error in destroy method: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while deleting user'
            ], 500);
        }
    }
}
