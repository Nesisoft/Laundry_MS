<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Order;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Exception;

class OrderApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $authUser = Auth::user();
        if (!$authUser) return response()->json(['message' => 'Unauthorized. Please log in.'], 401);

        try {
            $query = Order::with('customer');

            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            if ($request->has('service_id')) {
                $query->where('service_id', $request->service_id);
            }

            if ($request->has('archived')) {
                $query->where('archived', $request->boolean('archived'));
            } else {
                $query->where('archived', false);
            }

            $orders = $query->paginate($request->input('per_page', 15));

            return response()->json(['success' => true, 'data' => $orders]);
        } catch (Exception $e) {
            Log::error('Order index error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to fetch orders.'], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        $authUser = Auth::user();
        if (!$authUser) return response()->json(['message' => 'Unauthorized. Please log in.'], 401);

        if (!in_array($authUser->role, ['admin', 'manager'])) {
            return response()->json(['success' => false, 'message' => 'Access denied.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|exists:customers,id',
            'service_id' => 'required|exists:services,id',
            'status' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $order = Order::create([
                'customer_id' => $request->customer_id,
                'service_id' => $request->service_id,
                'status' => $request->status,
                'added_by' => $authUser->id,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'data' => $order
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Create order failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to create order'], 500);
        }
    }

    public function show($id): JsonResponse
    {
        $authUser = Auth::user();
        if (!$authUser) return response()->json(['message' => 'Unauthorized. Please log in.'], 401);

        try {
            $order = Order::with('customer')->find($id);
            return $order
                ? response()->json(['success' => true, 'data' => $order])
                : response()->json(['success' => false, 'message' => 'Order not found'], 404);
        } catch (Exception $e) {
            Log::error('Order show error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to fetch order'], 500);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        $authUser = Auth::user();
        if (!$authUser) return response()->json(['message' => 'Unauthorized. Please log in.'], 401);

        if (!in_array($authUser->role, ['admin', 'manager'])) {
            return response()->json(['success' => false, 'message' => 'Access denied.'], 403);
        }

        $order = Order::find($id);
        if (!$order) return response()->json(['success' => false, 'message' => 'Order not found'], 404);

        $validator = Validator::make($request->all(), [
            'status' => 'sometimes|string|max:255',
            'customer_id' => 'sometimes|exists:customers,id',
            'service_id' => 'sometimes|exists:services,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            if ($request->has('status')) $order->status = $request->status;
            if ($request->has('customer_id')) $order->customer_id = $request->customer_id;
            if ($request->has('service_id')) $order->service_id = $request->service_id;

            $order->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order updated successfully',
                'data' => $order
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Order update error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to update order'], 500);
        }
    }

    public function archive($id): JsonResponse
    {
        $authUser = Auth::user();
        if (!$authUser) return response()->json(['message' => 'Unauthorized. Please log in.'], 401);

        if (!in_array($authUser->role, ['admin', 'manager'])) {
            return response()->json(['success' => false, 'message' => 'Access denied.'], 403);
        }

        try {
            $order = Order::find($id);
            if (!$order) return response()->json(['success' => false, 'message' => 'Order not found'], 404);

            $order->archived = true;
            $order->save();

            return response()->json(['success' => true, 'message' => 'Order archived successfully']);
        } catch (Exception $e) {
            Log::error('Order archive error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to archive order'], 500);
        }
    }

    public function restore($id): JsonResponse
    {
        $authUser = Auth::user();
        if (!$authUser) return response()->json(['message' => 'Unauthorized. Please log in.'], 401);

        if (!in_array($authUser->role, ['admin', 'manager'])) {
            return response()->json(['success' => false, 'message' => 'Access denied.'], 403);
        }

        try {
            $order = Order::find($id);
            if (!$order || !$order->archived) {
                return response()->json(['success' => false, 'message' => 'Order not found or not archived'], 404);
            }

            $order->archived = false;
            $order->save();

            return response()->json(['success' => true, 'message' => 'Order restored successfully']);
        } catch (Exception $e) {
            Log::error('Order restore error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to restore order'], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        $authUser = Auth::user();
        if (!$authUser) return response()->json(['message' => 'Unauthorized. Please log in.'], 401);

        if (!in_array($authUser->role, ['admin', 'manager'])) {
            return response()->json(['success' => false, 'message' => 'Access denied.'], 403);
        }

        try {
            $order = Order::find($id);
            if (!$order) return response()->json(['success' => false, 'message' => 'Order not found'], 404);

            $order->delete();

            return response()->json(['success' => true, 'message' => 'Order deleted successfully']);
        } catch (Exception $e) {
            Log::error('Order delete error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to delete order'], 500);
        }
    }
    /**
     * Get recent orders.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRecentOrders()
    {
        $orders = Order::with('customer')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'customer_name' => $order->customer->first_name . ' ' . $order->customer->last_name,
                    'total' => $order->total,
                    'created_at' => $order->created_at
                ];
            });

        return response()->json([
            'success' => true,
            'orders' => $orders
        ]);
    }
}
