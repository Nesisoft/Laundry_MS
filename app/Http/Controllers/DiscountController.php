<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class DiscountController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $authUser = Auth::user();
        if (!$authUser) return response()->json(['message' => 'Unauthorized.'], 401);

        try {
            $query = Discount::query();

            if ($request->has('archived')) {
                $query->where('archived', $request->boolean('archived'));
            } else {
                $query->where('archived', false);
            }

            $perPage = $request->input('per_page', 15);
            $discounts = $query->paginate($perPage);

            return response()->json(['success' => true, 'data' => $discounts], 200);
        } catch (Exception $e) {
            Log::error('Discount index error', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to fetch discounts'], 500);
        }
    }

    public function show($id): JsonResponse
    {
        $authUser = Auth::user();
        if (!$authUser) return response()->json(['message' => 'Unauthorized.'], 401);

        try {
            $discount = Discount::find($id);
            return $discount
                ? response()->json(['success' => true, 'data' => $discount])
                : response()->json(['success' => false, 'message' => 'Discount not found.'], 404);
        } catch (Exception $e) {
            Log::error('Show discount error', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Error retrieving discount.'], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        $authUser = Auth::user();
        if (!$authUser) return response()->json(['message' => 'Unauthorized.'], 401);

        if (!in_array($authUser->role, ['admin', 'manager'])) {
            return response()->json(['success' => false, 'message' => 'Access denied.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|unique:discounts,name',
            'type' => 'required|in:percentage,amount',
            'value' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
            'expiration_date' => 'nullable|date|after_or_equal:today'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $discount = new Discount($request->only(['type', 'value', 'description', 'expiration_date']));
            $discount->added_by = $authUser->id;
            if ($request->has('name')) $discount->name = $request->name;
            $discount->save();

            return response()->json(['success' => true, 'message' => 'Discount created.', 'data' => $discount], 201);
        } catch (Exception $e) {
            Log::error('Store discount error', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to create discount'], 500);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        $authUser = Auth::user();
        if (!$authUser) return response()->json(['message' => 'Unauthorized.'], 401);

        if (!in_array($authUser->role, ['admin', 'manager'])) {
            return response()->json(['success' => false, 'message' => 'Access denied.'], 403);
        }

        $discount = Discount::find($id);
        if (!$discount) return response()->json(['success' => false, 'message' => 'Discount not found.'], 404);

        $validator = Validator::make($request->all(), [
            'type' => 'sometimes|in:percentage,amount',
            'value' => 'sometimes|numeric|min:0',
            'description' => 'nullable|string|max:255',
            'expiration_date' => 'nullable|date|after_or_equal:today'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $discount->update($request->only(['type', 'value', 'description', 'expiration_date']));
            return response()->json(['success' => true, 'message' => 'Discount updated.', 'data' => $discount]);
        } catch (Exception $e) {
            Log::error('Update discount error', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to update discount'], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        $authUser = Auth::user();
        if (!$authUser) return response()->json(['message' => 'Unauthorized.'], 401);

        if (!in_array($authUser->role, ['admin', 'manager'])) {
            return response()->json(['success' => false, 'message' => 'Access denied.'], 403);
        }

        try {
            $discount = Discount::find($id);
            if (!$discount) return response()->json(['success' => false, 'message' => 'Discount not found.'], 404);

            $discount->delete();
            return response()->json(['success' => true, 'message' => 'Discount deleted successfully.']);
        } catch (Exception $e) {
            Log::error('Delete discount error', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to delete discount'], 500);
        }
    }

    public function archive($id): JsonResponse
    {
        $authUser = Auth::user();
        if (!$authUser) return response()->json(['message' => 'Unauthorized.'], 401);

        if (!in_array($authUser->role, ['admin', 'manager'])) {
            return response()->json(['success' => false, 'message' => 'Access denied.'], 403);
        }

        try {
            $discount = Discount::find($id);
            if (!$discount) return response()->json(['success' => false, 'message' => 'Discount not found.'], 404);

            $discount->archived = true;
            $discount->save();

            return response()->json(['success' => true, 'message' => 'Discount archived.']);
        } catch (Exception $e) {
            Log::error('Archive discount error', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to archive discount'], 500);
        }
    }

    public function restore($id): JsonResponse
    {
        $authUser = Auth::user();
        if (!$authUser) return response()->json(['message' => 'Unauthorized.'], 401);

        if (!in_array($authUser->role, ['admin', 'manager'])) {
            return response()->json(['success' => false, 'message' => 'Access denied.'], 403);
        }

        try {
            $discount = Discount::find($id);
            if (!$discount || !$discount->archived) {
                return response()->json(['success' => false, 'message' => 'Discount not found or not archived.'], 404);
            }

            $discount->archived = false;
            $discount->save();

            return response()->json(['success' => true, 'message' => 'Discount restored.']);
        } catch (Exception $e) {
            Log::error('Restore discount error', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to restore discount'], 500);
        }
    }
}
