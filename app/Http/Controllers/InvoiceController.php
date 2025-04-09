<?php

namespace App\Http\Controllers;

use App\Models\CustomerDiscount;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use Exception;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Http;

class InvoiceController extends Controller
{
    public function index(): JsonResponse
    {
        $authUser = Auth::user();
        if (!$authUser) return response()->json(['message' => 'Unauthorized. Please log in.'], 401);

        try {
            $invoices = Invoice::with('order')->paginate(15);
            return response()->json(['success' => true, 'data' => $invoices]);
        } catch (Exception $e) {
            Log::error('Fetch invoices error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error fetching invoices'], 500);
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
            'order_id' => 'required|exists:orders,id'
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

            $order = Order::with('customer')->find($request->order_id);
            if (!$order) {
                return response()->json(['success' => false, 'message' => 'Order not found.'], 404);
            }

            $customerId = $order->customer_id;

            // Get total amount from order items
            $totalAmount = OrderItem::where('order_id', $order->id)
                ->sum(DB::raw('amount * quantity'));

            $discountAmount = 0;

            // Check if customer has a valid discount
            $customerDiscount = CustomerDiscount::where('customer_id', $customerId)
                ->whereHas('discount', function ($q) {
                    $q->where('expiration_date', '>=', now())->where('archived', false);
                })
                ->latest()
                ->first();

            if ($customerDiscount && $customerDiscount->discount) {
                $discount = $customerDiscount->discount;

                if ($discount->type === 'percentage') {
                    $discountAmount = ($discount->value / 100) * $totalAmount;
                } elseif ($discount->type === 'amount') {
                    $discountAmount = $discount->value;
                }

                // Prevent discount exceeding total amount
                if ($discountAmount > $totalAmount) {
                    $discountAmount = $totalAmount;
                }
            }

            $invoice = Invoice::create([
                'order_id' => $order->id,
                'amount' => $totalAmount,
                'discount_amount' => $discountAmount,
                'status' => 'unpaid',
                'smsed' => false,
                'archived' => false,
                'added_by' => $authUser->id
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Invoice created successfully.',
                'data' => $invoice
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error creating invoice: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to create invoice.'], 500);
        }
    }

    public function show($id): JsonResponse
    {
        $authUser = Auth::user();
        if (!$authUser) return response()->json(['message' => 'Unauthorized. Please log in.'], 401);

        try {
            $invoice = Invoice::with('order')->findOrFail($id);
            return response()->json(['success' => true, 'data' => $invoice]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Invoice not found'], 404);
        }
    }

    public function sendInvoiceSMS($invoiceId): JsonResponse
    {
        $authUser = Auth::user();
        if (!$authUser) {
            return response()->json(['message' => 'Unauthorized. Please log in.'], 401);
        }

        try {
            $invoice = Invoice::with('order.customer')->findOrFail($invoiceId);

            $customer = $invoice->order->customer ?? null;

            if (!$customer || !$customer->phone_number) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer or phone number not found.'
                ], 404);
            }

            $phone = $customer->phone_number;

            $message = "Hello {$customer->first_name}, your invoice is ready.\n" .
                "Amount: GH₵" . number_format($invoice->amount, 2) . "\n" .
                "Discount: GH₵" . number_format($invoice->discount_amount, 2) . "\n" .
                "Payable: GH₵" . number_format($invoice->actual_amount, 2) . "\n" .
                "Invoice ID: #{$invoice->id}";

            // Send SMS via Twilio
            $twilio = new Client(env('TWILIO_SID'), env('TWILIO_TOKEN'));
            $twilio->messages->create(
                $phone,
                [
                    'from' => env('TWILIO_FROM'),
                    'body' => $message
                ]
            );

            // Mark invoice as SMSed
            $invoice->smsed = true;
            $invoice->save();

            return response()->json([
                'success' => true,
                'message' => 'Invoice sent via SMS successfully.'
            ]);
        } catch (\Exception $e) {
            Log::error('Error sending invoice SMS: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send invoice SMS.'
            ], 500);
        }
    }
}
