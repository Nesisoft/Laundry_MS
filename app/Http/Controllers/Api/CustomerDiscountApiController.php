<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CustomerDiscount;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

class CustomerDiscountApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $query = CustomerDiscount::query()->with(['customer', 'discount']);

            if ($request->has('customer_id')) {
                $query->where('customer_id', $request->customer_id);
            }

            if ($request->has('discount_id')) {
                $query->where('discount_id', $request->discount_id);
            }

            $discounts = $query->paginate($request->input('per_page', 15));
            return response()->json(['success' => true, 'data' => $discounts]);
        } catch (Exception $e) {
            Log::error('Fetch customer discounts failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Unable to fetch discounts.'], 500);
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
            'customer_id' => 'required|exists:customers,id',
            'discount_id' => 'required|exists:discounts,id',
            'customer_expiration_date' => 'nullable|date|after_or_equal:today'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $discount = CustomerDiscount::create([
                'customer_id' => $request->customer_id,
                'discount_id' => $request->discount_id,
                'customer_expiration_date' => $request->customer_expiration_date,
                'added_by' => $authUser->id,
            ]);

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Discount assigned successfully.', 'data' => $discount], 201);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Create customer discount failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Unable to assign discount.'], 500);
        }
    }

    public function show($id): JsonResponse
    {
        $authUser = Auth::user();
        if (!$authUser) return response()->json(['message' => 'Unauthorized.'], 401);

        try {
            $record = CustomerDiscount::with(['customer', 'discount'])->find($id);

            return $record
                ? response()->json(['success' => true, 'data' => $record])
                : response()->json(['success' => false, 'message' => 'Record not found.'], 404);
        } catch (Exception $e) {
            Log::error('Show customer discount failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Unable to retrieve record.'], 500);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        $authUser = Auth::user();
        if (!$authUser) return response()->json(['message' => 'Unauthorized.'], 401);

        if (!in_array($authUser->role, ['admin', 'manager'])) {
            return response()->json(['success' => false, 'message' => 'Access denied.'], 403);
        }

        try {
            $discount = CustomerDiscount::find($id);

            if (!$discount) {
                return response()->json(['success' => false, 'message' => 'Record not found.'], 404);
            }

            $validator = Validator::make($request->all(), [
                'customer_id' => 'sometimes|exists:customers,id',
                'discount_id' => 'sometimes|exists:discounts,id',
                'customer_expiration_date' => 'nullable|date|after_or_equal:today'
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            $discount->update($request->only(['customer_id', 'discount_id', 'customer_expiration_date']));

            return response()->json(['success' => true, 'message' => 'Customer discount updated.', 'data' => $discount]);
        } catch (Exception $e) {
            Log::error('Update customer discount failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Unable to update.'], 500);
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
            $discount = CustomerDiscount::find($id);
            if (!$discount) {
                return response()->json(['success' => false, 'message' => 'Record not found.'], 404);
            }

            $discount->delete();
            return response()->json(['success' => true, 'message' => 'Discount record deleted.']);
        } catch (Exception $e) {
            Log::error('Delete customer discount failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Unable to delete.'], 500);
        }
    }
}
