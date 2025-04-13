<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\InvoicePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use Exception;

class InvoicePaymentApiController extends Controller
{
    public function index(): JsonResponse
    {
        $authUser = Auth::user();
        if (!$authUser) return response()->json(['message' => 'Unauthorized. Please log in.'], 401);

        try {
            $payments = InvoicePayment::with('invoice')->paginate(15);
            return response()->json(['success' => true, 'data' => $payments]);
        } catch (Exception $e) {
            Log::error('Fetch invoice payments error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to fetch payments'], 500);
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
            'invoice_id' => 'required|exists:invoices,id',
            'amount' => 'required|numeric',
            'method' => 'required|in:Cash,MoMo,Card',
            'status' => 'required|in:fully paid,partly paid'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $payment = InvoicePayment::create([
                'invoice_id' => $request->invoice_id,
                'amount' => $request->amount,
                'method' => $request->method,
                'status' => $request->status,
                'added_by' => $authUser->id
            ]);

            DB::commit();
            return response()->json(['success' => true, 'data' => $payment], 201);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Create invoice payment error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Payment creation failed'], 500);
        }
    }
}
