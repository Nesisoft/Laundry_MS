<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $authUser = Auth::user();

        if (!$authUser) {
            return response()->json(['message' => 'Unauthorized. Please log in.'], 401);
        }

        try {
            $query = Item::query();

            // Apply filters if provided

            if ($request->has('added_by')) {
                $query->where('added_by', $request->added_by);
            }

            if ($request->has('category')) {
                $query->where('category', $request->category);
            }

            if ($request->has('archived')) {
                $query->where('archived', $request->boolean('archived'));
            } else {
                $query->where('archived', false); // Default: show non-archived
            }

            // Pagination
            $perPage = $request->input('per_page', 15);
            $items = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $items
            ]);
        } catch (Exception $e) {
            Log::error('Error in items index method: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving items'
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        $authUser = Auth::user();

        if (!$authUser) {
            return response()->json(['message' => 'Unauthorized. Please log in.'], 401);
        }

        try {
            $item = Item::find($id);
            return $item
                ? response()->json(['success' => true, 'data' => $item])
                : response()->json(['success' => false, 'message' => 'Item not found'], 404);
        } catch (Exception $e) {
            Log::error('Error in items show method: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving item'
            ], 500);
        }
    }

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

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:items,name',
            'amount' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $item = new Item($request->only(['name', 'amount']));
            $item->added_by = Auth::id();

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('public/uploads/items');
                $item->image = str_replace('public/', '', $path);
            }

            $item->save();
            DB::commit();
            return response()->json(['success' => true, 'data' => $item], 201);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Item creation failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to add item.'], 500);
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
            return response()->json([
                'success' => false,
                'message' => 'Access denied.'
            ], 403);
        }

        $item = Item::find($id);

        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Item not found.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|unique:items,name,' . $item->id,
            'amount' => 'sometimes|required|numeric',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            if ($request->has('name')) $item->name = $request->name;
            if ($request->has('amount')) $item->amount = $request->amount;

            if ($request->hasFile('image')) {
                if ($item->image && Storage::exists('public/' . $item->image)) {
                    Storage::delete('public/' . $item->image);
                }
                $path = $request->file('image')->store('public/uploads/items');
                $item->image = str_replace('public/', '', $path);
            }

            $item->save();
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Item updated successfully.', 'data' => $item]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Update item error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to update item.'], 500);
        }
    }

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
            $item = Item::find($id);
            if (!$item) {
                return response()->json(['success' => false, 'message' => 'Item not found.'], 404);
            }

            $item->delete();
            return response()->json(['success' => true, 'message' => 'Item deleted successfully.']);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Delete item error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to Delete item.'], 500);
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
            return response()->json([
                'success' => false,
                'message' => 'Access denied.'
            ], 403);
        }

        try {
            $item = Item::find($id);
            if (!$item) return response()->json(['success' => false, 'message' => 'Item not found.'], 404);

            $item->archived = true;
            $item->save();

            return response()->json(['success' => true, 'message' => 'Item archived successfully.']);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Delete item error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to Delete item.'], 500);
        }
    }

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
            $item = Item::find($id);
            if (!$item || !$item->archived) {
                return response()->json(['success' => false, 'message' => 'Item not found or not archived.'], 404);
            }

            $item->archived = false;
            $item->save();

            return response()->json(['success' => true, 'message' => 'Item restored successfully.']);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Delete item error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to Delete item.'], 500);
        }
    }
}
