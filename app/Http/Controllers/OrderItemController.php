<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Exception;

class OrderItemController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $authUser = Auth::user();
            if (!$authUser) return response()->json(['message' => 'Unauthorized'], 401);

            $items = OrderItem::with(['order', 'item'])->paginate(15);
            return response()->json(['success' => true, 'data' => $items]);
        } catch (Exception $e) {
            Log::error('Fetch order items failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to fetch order items'], 500);
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
            'order_id' => 'required|exists:orders,id',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.amount' => 'nullable|numeric|min:0' // optional, can be auto-filled
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $orderId = $request->order_id;
            $existingItems = OrderItem::where('order_id', $orderId)
                ->pluck('item_id')
                ->toArray();

            $createdItems = [];

            foreach ($request->items as $entry) {
                $itemId = $entry['item_id'];

                // Check for duplicate item in current order
                if (in_array($itemId, $existingItems)) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Item ID {$itemId} already exists in the order."
                    ], 409);
                }

                // Get item amount from DB if not provided
                $amount = $entry['amount'] ?? Item::find($itemId)?->amount;

                if ($amount === null) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Amount could not be determined for item ID {$itemId}."
                    ], 422);
                }

                $orderItem = new OrderItem([
                    'order_id' => $orderId,
                    'item_id' => $itemId,
                    'amount' => $amount,
                    'quantity' => $entry['quantity'],
                    'added_by' => $authUser->id,
                ]);

                $orderItem->save();
                $createdItems[] = $orderItem;

                $existingItems[] = $itemId; // Update the list to track duplicates
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order items added successfully.',
                'data' => $createdItems
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error adding order items: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to add order items.'
            ], 500);
        }
    }


    public function destroy($id): JsonResponse
    {
        try {
            $authUser = Auth::user();
            if (!$authUser) return response()->json(['message' => 'Unauthorized.'], 401);

            if (!in_array($authUser->role, ['admin', 'manager'])) {
                return response()->json(['success' => false, 'message' => 'Access denied.'], 403);
            }

            $item = OrderItem::find($id);

            if (!$item) return response()->json(['success' => false, 'message' => 'Order item not found'], 404);

            $item->delete();

            return response()->json(['success' => true, 'message' => 'Order item deleted successfully']);
        } catch (Exception $e) {
            Log::error('Delete order item failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to delete order item'], 500);
        }
    }
}
