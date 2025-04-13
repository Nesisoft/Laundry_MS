<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Customer;
use App\Models\DeliveryRequest;
use App\Models\DeliveryRequestPayment;
use App\Models\DeliveryRequestDriverAssignment;
use App\Models\Employee;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class DeliveryRequestApiController extends Controller
{
    /**
     * Display a listing of the delivery requests.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $query = DeliveryRequest::query();

            // Apply filters if provided
            if ($request->has('customer_id')) {
                $query->where('customer_id', $request->customer_id);
            }

            if ($request->has('order_id')) {
                $query->where('order_id', $request->order_id);
            }

            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            if ($request->has('date_from')) {
                $query->whereDate('date', '>=', $request->date_from);
            }

            if ($request->has('date_to')) {
                $query->whereDate('date', '<=', $request->date_to);
            }

            if ($request->has('archived')) {
                $query->where('archived', $request->boolean('archived'));
            } else {
                $query->where('archived', false); // Default: show non-archived
            }

            // Include relationships if requested
            if ($request->has('with_customer') && $request->boolean('with_customer')) {
                $query->with('customer');
            }

            if ($request->has('with_order') && $request->boolean('with_order')) {
                $query->with('order');
            }

            if ($request->has('with_payments') && $request->boolean('with_payments')) {
                $query->with('payments');
            }

            if ($request->has('with_driver_assignments') && $request->boolean('with_driver_assignments')) {
                $query->with('driverAssignments');
            }

            // Pagination
            $perPage = $request->input('per_page', 15);
            $deliveryRequests = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $deliveryRequests
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve delivery requests',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created delivery request in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|exists:customers,id',
            'location' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'amount' => 'required|numeric|min:0',
            'note' => 'nullable|string',
            'status' => 'sometimes|in:pending,in-progress,completed,cancelled',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        DB::beginTransaction();

        try {
            $deliveryRequest = new DeliveryRequest();
            $deliveryRequest->customer_id = $request->customer_id;
            $deliveryRequest->location = $request->location;
            $deliveryRequest->latitude = $request->latitude;
            $deliveryRequest->longitude = $request->longitude;
            $deliveryRequest->date = $request->date;
            $deliveryRequest->time = $request->time;
            $deliveryRequest->amount = $request->amount;
            $deliveryRequest->note = $request->note;
            $deliveryRequest->status = $request->status ?? 'pending';
            $deliveryRequest->added_by = Auth::id(); // Assuming authentication is set up
            $deliveryRequest->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Delivery request created successfully',
                'data' => $deliveryRequest
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to create delivery request',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified delivery request.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $deliveryRequest = DeliveryRequest::with(['customer', 'service', 'payments', 'driverAssignments'])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $deliveryRequest
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Delivery request not found or error retrieving data',
                'error' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Update the specified delivery request in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'sometimes|exists:customers,id',
            'location' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'date' => 'sometimes|date',
            'time' => 'sometimes|date_format:H:i',
            'amount' => 'sometimes|numeric|min:0',
            'note' => 'nullable|string',
            'status' => 'sometimes|in:pending,in-progress,completed,cancelled',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        DB::beginTransaction();

        try {
            $deliveryRequest = DeliveryRequest::findOrFail($id);

            if ($request->has('customer_id')) {
                $deliveryRequest->customer_id = $request->customer_id;
            }

            if ($request->has('location')) {
                $deliveryRequest->location = $request->location;
            }

            if ($request->has('latitude')) {
                $deliveryRequest->latitude = $request->latitude;
            }

            if ($request->has('longitude')) {
                $deliveryRequest->longitude = $request->longitude;
            }

            if ($request->has('date')) {
                $deliveryRequest->date = $request->date;
            }

            if ($request->has('time')) {
                $deliveryRequest->time = $request->time;
            }

            if ($request->has('amount')) {
                $deliveryRequest->amount = $request->amount;
            }

            if ($request->has('note')) {
                $deliveryRequest->note = $request->note;
            }

            if ($request->has('status')) {
                $deliveryRequest->status = $request->status;
            }

            $deliveryRequest->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Delivery request updated successfully',
                'data' => $deliveryRequest
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to update delivery request',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the status of the specified delivery request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,in-progress,completed,cancelled',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        DB::beginTransaction();

        try {
            $deliveryRequest = DeliveryRequest::findOrFail($id);
            $deliveryRequest->status = $request->status;
            $deliveryRequest->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Delivery request status updated successfully',
                'data' => $deliveryRequest
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to update delivery request status',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function archive($id)
    {
        return $this->toggleArchive($id, true);
    }

    public function restore($id)
    {
        return $this->toggleArchive($id, false);
    }

    /**
     * Archive or unarchive the specified delivery request.
     *
     * @param  int  $id
     * @param  bool  $status
     * @return \Illuminate\Http\Response
     */
    private function toggleArchive($id, $status)
    {
        $authUser = Auth::user();
        if (!$authUser) return response()->json(['message' => 'Unauthorized.'], 401);

        DB::beginTransaction();

        try {
            $deliveryRequest = DeliveryRequest::findOrFail($id);
            $deliveryRequest->archived = $status;
            $deliveryRequest->save();

            $action = $status ? 'archived' : 'unarchived';

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Delivery request {$action} successfully",
                'data' => $deliveryRequest
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle archive status',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified delivery request from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $deliveryRequest = DeliveryRequest::findOrFail($id);

            // Delete related records first
            $deliveryRequest->payments()->each->delete();
            $deliveryRequest->driverAssignments()->each->delete();

            // Delete the delivery request
            $deliveryRequest->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Delivery request deleted successfully'
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete delivery request',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Assign a driver to a delivery request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function assignDriver(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        DB::beginTransaction();

        try {
            // Check if the delivery request exists
            $deliveryRequest = DeliveryRequest::findOrFail($id);

            // Check if the employee exists and is not archived
            $employee = Employee::where('id', $request->employee_id)
                ->where('archived', false)
                ->firstOrFail();

            // Create a new driver assignment
            $assignment = new DeliveryRequestDriverAssignment();
            $assignment->employee_id = $request->employee_id;
            $assignment->request_id = $id;
            $assignment->status = 'in-progress';
            $assignment->added_by = Auth::id();
            $assignment->save();

            // Update the delivery request status to 'in-progress'
            $deliveryRequest->status = 'in-progress';
            $deliveryRequest->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Driver assigned successfully',
                'data' => [
                    'delivery_request' => $deliveryRequest,
                    'assignment' => $assignment
                ]
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to assign driver',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the status of a driver assignment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @param  int  $assignmentId
     * @return \Illuminate\Http\Response
     */
    public function updateDriverAssignmentStatus(Request $request, $id, $assignmentId)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:in-progress,completed,cancelled',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        DB::beginTransaction();

        try {
            // Check if the delivery request exists
            $deliveryRequest = DeliveryRequest::findOrFail($id);

            // Check if the assignment exists and belongs to this delivery request
            $assignment = DeliveryRequestDriverAssignment::where('id', $assignmentId)
                ->where('request_id', $id)
                ->firstOrFail();

            // Update the assignment status
            $assignment->status = $request->status;
            $assignment->save();

            // Update the delivery request status if assignment is completed or cancelled
            if ($request->status == 'completed') {
                $deliveryRequest->status = 'completed';
                $deliveryRequest->save();
            } elseif ($request->status == 'cancelled') {
                // If there are no other active assignments, revert the delivery request to 'accepted'
                $activeAssignments = DeliveryRequestDriverAssignment::where('request_id', $id)
                    ->where('status', 'in-progress')
                    ->where('id', '!=', $assignmentId)
                    ->count();

                if ($activeAssignments == 0) {
                    $deliveryRequest->status = 'pending';
                    $deliveryRequest->save();
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Driver assignment status updated successfully',
                'data' => [
                    'delivery_request' => $deliveryRequest,
                    'assignment' => $assignment
                ]
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to update driver assignment status',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Record a payment for a delivery request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function recordPayment(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0.01',
            'method' => 'required|in:Cash,MoMo,Card',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        DB::beginTransaction();

        try {
            // Check if the delivery request exists
            $deliveryRequest = DeliveryRequest::findOrFail($id);

            // Calculate the total payments made so far
            $totalPaid = DeliveryRequestPayment::where('request_id', $id)
                ->sum('amount');

            // Calculate the amount still to be paid
            $remaining = $deliveryRequest->amount - $totalPaid;

            // Ensure the payment amount doesn't exceed the remaining amount
            if ($request->amount > $remaining) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment amount exceeds the remaining balance',
                    'data' => [
                        'total_amount' => $deliveryRequest->amount,
                        'already_paid' => $totalPaid,
                        'remaining' => $remaining,
                        'attempted_payment' => $request->amount
                    ]
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            // Create a new payment record
            $payment = new DeliveryRequestPayment();
            $payment->request_id = $id;
            $payment->amount = $request->amount;
            $payment->method = $request->method;

            // If this payment completes the full amount, set status to 'paid'
            // Otherwise, set it as 'unpaid' (partially paid)
            if (abs($request->amount - $remaining) < 0.01) {
                $payment->status = 'paid';
            } else {
                $payment->status = 'unpaid';
            }

            $payment->added_by = Auth::id();
            $payment->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Payment recorded successfully',
                'data' => [
                    'payment' => $payment,
                    'total_amount' => $deliveryRequest->amount,
                    'total_paid' => $totalPaid + $request->amount,
                    'remaining' => $remaining - $request->amount
                ]
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to record payment',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get all payments for a delivery request.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getPayments($id)
    {
        try {
            // Check if the delivery request exists
            $deliveryRequest = DeliveryRequest::findOrFail($id);

            // Get all payments for this delivery request
            $payments = DeliveryRequestPayment::where('request_id', $id)
                ->orderBy('created_at', 'desc')
                ->get();

            // Calculate the total paid amount
            $totalPaid = $payments->sum('amount');

            // Calculate the remaining amount
            $remaining = $deliveryRequest->amount - $totalPaid;

            return response()->json([
                'success' => true,
                'data' => [
                    'delivery_request' => $deliveryRequest,
                    'payments' => $payments,
                    'summary' => [
                        'total_amount' => $deliveryRequest->amount,
                        'total_paid' => $totalPaid,
                        'remaining' => $remaining,
                        'payment_status' => $remaining <= 0 ? 'fully paid' : 'partially paid'
                    ]
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve payments',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get all payments for a delivery request.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllPayments()
    {
        try {
            // Get all payments
            $payments = DeliveryRequestPayment::with('deliveryRequest')
                ->orderBy('created_at', 'desc')
                ->get();

            // Calculate total paid amount
            $totalPaid = $payments->sum('amount');

            // Get all delivery requests
            $deliveryRequests = DeliveryRequest::all();

            // Calculate total amount from all requests
            $totalAmount = $deliveryRequests->sum('amount');

            // Calculate remaining amount
            $remaining = $totalAmount - $totalPaid;

            return response()->json([
                'success' => true,
                'data' => [
                    'delivery_requests' => $deliveryRequests,
                    'payments' => $payments,
                    'summary' => [
                        'total_amount' => $totalAmount,
                        'total_paid' => $totalPaid,
                        'remaining' => $remaining,
                        'payment_status' => $remaining <= 0 ? 'fully paid' : 'partially paid'
                    ]
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve payments',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
