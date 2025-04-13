<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\PickupRequest;
use App\Models\PickupRequestPayment;
use App\Models\PickupRequestDriverAssignment;
use App\Models\Employee;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PickupRequestApiController extends Controller
{
    /**
     * Display a listing of the pickup requests.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $query = PickupRequest::query();

            // Apply filters if provided
            if ($request->has('customer_id')) {
                $query->where('customer_id', $request->customer_id);
            }

            if ($request->has('service_id')) {
                $query->where('service_id', $request->service_id);
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

            if ($request->has('with_service') && $request->boolean('with_service')) {
                $query->with('service');
            }

            if ($request->has('with_payments') && $request->boolean('with_payments')) {
                $query->with('payments');
            }

            if ($request->has('with_driver_assignments') && $request->boolean('with_driver_assignments')) {
                $query->with('driverAssignments');
            }

            // Pagination
            $perPage = $request->input('per_page', 15);
            $pickupRequests = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $pickupRequests
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve pickup requests',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created pickup request in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|exists:customers,id',
            'service_id' => 'required|exists:services,id',
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
            $pickupRequest = new PickupRequest();
            $pickupRequest->customer_id = $request->customer_id;
            $pickupRequest->service_id = $request->service_id;
            $pickupRequest->location = $request->location;
            $pickupRequest->latitude = $request->latitude;
            $pickupRequest->longitude = $request->longitude;
            $pickupRequest->date = $request->date;
            $pickupRequest->time = $request->time;
            $pickupRequest->amount = $request->amount;
            $pickupRequest->note = $request->note;
            $pickupRequest->status = $request->status ?? 'pending';
            $pickupRequest->added_by = Auth::id(); // Assuming authentication is set up
            $pickupRequest->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pickup request created successfully',
                'data' => $pickupRequest
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to create pickup request',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified pickup request.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $pickupRequest = PickupRequest::with(['customer', 'service', 'payments', 'driverAssignments'])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $pickupRequest
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Pickup request not found or error retrieving data',
                'error' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Update the specified pickup request in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'sometimes|exists:customers,id',
            'service_id' => 'sometimes|exists:services,id',
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
            $pickupRequest = PickupRequest::findOrFail($id);

            if ($request->has('customer_id')) {
                $pickupRequest->customer_id = $request->customer_id;
            }

            if ($request->has('service_id')) {
                $pickupRequest->service_id = $request->service_id;
            }

            if ($request->has('location')) {
                $pickupRequest->location = $request->location;
            }

            if ($request->has('latitude')) {
                $pickupRequest->latitude = $request->latitude;
            }

            if ($request->has('longitude')) {
                $pickupRequest->longitude = $request->longitude;
            }

            if ($request->has('date')) {
                $pickupRequest->date = $request->date;
            }

            if ($request->has('time')) {
                $pickupRequest->time = $request->time;
            }

            if ($request->has('amount')) {
                $pickupRequest->amount = $request->amount;
            }

            if ($request->has('note')) {
                $pickupRequest->note = $request->note;
            }

            if ($request->has('status')) {
                $pickupRequest->status = $request->status;
            }

            $pickupRequest->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pickup request updated successfully',
                'data' => $pickupRequest
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to update pickup request',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the status of the specified pickup request.
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
            $pickupRequest = PickupRequest::findOrFail($id);
            $pickupRequest->status = $request->status;
            $pickupRequest->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pickup request status updated successfully',
                'data' => $pickupRequest
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to update pickup request status',
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
     * Archive or unarchive the specified pickup request.
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
            $pickupRequest = PickupRequest::findOrFail($id);
            $pickupRequest->archived = $status;
            $pickupRequest->save();

            $action = $status ? 'archived' : 'unarchived';

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Pickup request {$action} successfully",
                'data' => $pickupRequest
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
     * Remove the specified pickup request from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $pickupRequest = PickupRequest::findOrFail($id);

            // Delete related records first
            $pickupRequest->payments->each->delete();
            $pickupRequest->driverAssignments->each->delete();

            // Delete the pickup request
            $pickupRequest->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pickup request deleted successfully'
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete pickup request',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Assign a driver to a pickup request.
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
            // Check if the pickup request exists
            $pickupRequest = PickupRequest::findOrFail($id);

            // Check if the employee exists and is not archived
            $employee = Employee::where('id', $request->employee_id)
                ->where('archived', false)
                ->firstOrFail();

            // Create a new driver assignment
            $assignment = new PickupRequestDriverAssignment();
            $assignment->employee_id = $request->employee_id;
            $assignment->request_id = $id;
            $assignment->status = 'in-progress';
            $assignment->added_by = Auth::id();
            $assignment->save();

            // Update the pickup request status to 'in-progress'
            $pickupRequest->status = 'in-progress';
            $pickupRequest->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Driver assigned successfully',
                'data' => [
                    'pickup_request' => $pickupRequest,
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
            // Check if the pickup request exists
            $pickupRequest = PickupRequest::findOrFail($id);

            // Check if the assignment exists and belongs to this pickup request
            $assignment = PickupRequestDriverAssignment::where('id', $assignmentId)
                ->where('request_id', $id)
                ->firstOrFail();

            // Update the assignment status
            $assignment->status = $request->status;
            $assignment->save();

            // Update the pickup request status if assignment is completed or cancelled
            if ($request->status == 'completed') {
                $pickupRequest->status = 'completed';
                $pickupRequest->save();
            } elseif ($request->status == 'cancelled') {
                // If there are no other active assignments, revert the pickup request to 'accepted'
                $activeAssignments = PickupRequestDriverAssignment::where('request_id', $id)
                    ->where('status', 'in-progress')
                    ->where('id', '!=', $assignmentId)
                    ->count();

                if ($activeAssignments == 0) {
                    $pickupRequest->status = 'accepted';
                    $pickupRequest->save();
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Driver assignment status updated successfully',
                'data' => [
                    'pickup_request' => $pickupRequest,
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
     * Record a payment for a pickup request.
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
            // Check if the pickup request exists
            $pickupRequest = PickupRequest::findOrFail($id);

            // Calculate the total payments made so far
            $totalPaid = PickupRequestPayment::where('request_id', $id)
                ->sum('amount');

            // Calculate the amount still to be paid
            $remaining = $pickupRequest->amount - $totalPaid;

            // Ensure the payment amount doesn't exceed the remaining amount
            if ($request->amount > $remaining) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment amount exceeds the remaining balance',
                    'data' => [
                        'total_amount' => $pickupRequest->amount,
                        'already_paid' => $totalPaid,
                        'remaining' => $remaining,
                        'attempted_payment' => $request->amount
                    ]
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            // Create a new payment record
            $payment = new PickupRequestPayment();
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
                    'total_amount' => $pickupRequest->amount,
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
     * Get all payments for a pickup request.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getPayments($id)
    {
        try {
            // Check if the pickup request exists
            $pickupRequest = PickupRequest::findOrFail($id);

            // Get all payments for this pickup request
            $payments = PickupRequestPayment::where('request_id', $id)
                ->orderBy('created_at', 'desc')
                ->get();

            // Calculate the total paid amount
            $totalPaid = $payments->sum('amount');

            // Calculate the remaining amount
            $remaining = $pickupRequest->amount - $totalPaid;

            return response()->json([
                'success' => true,
                'data' => [
                    'pickup_request' => $pickupRequest,
                    'payments' => $payments,
                    'summary' => [
                        'total_amount' => $pickupRequest->amount,
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
     * Get all payments for a pickup request.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllPayments()
    {
        try {
            // Get all pickup requests
            $pickupRequests = PickupRequest::all();

            // Get all payments
            $payments = PickupRequestPayment::with('pickupRequest') // Assuming the relation is named pickupRequest
                ->orderBy('created_at', 'desc')
                ->get();

            // Calculate the total amount of all pickup requests
            $totalAmount = $pickupRequests->sum('amount');

            // Calculate the total paid amount
            $totalPaid = $payments->sum('amount');

            // Calculate the remaining amount
            $remaining = $totalAmount - $totalPaid;

            return response()->json([
                'success' => true,
                'data' => [
                    'pickup_requests' => $pickupRequests,
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
            // Error handling remains the same
        }
    }
}
