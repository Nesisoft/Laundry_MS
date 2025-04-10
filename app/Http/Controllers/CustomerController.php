<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Address;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    /**
     * Display a listing of the customers.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $authUser = Auth::user();
        if (!$authUser) return response()->json(['message' => 'Unauthorized.'], 401);

        try {
            $query = Customer::query();

            // Apply filters if provided
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('phone_number', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            }

            if ($request->has('sex')) {
                $query->where('sex', $request->sex);
            }

            if ($request->has('archived')) {
                $query->where('archived', $request->boolean('archived'));
            } else {
                $query->where('archived', false); // Default: show non-archived
            }

            // Include address if requested
            if ($request->has('with_address') && $request->boolean('with_address')) {
                $query->with('address');
            }

            // Pagination
            $perPage = $request->input('per_page', 15);
            $customers = $query->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $customers
            ]);
        } catch (Exception $e) {
            Log::error('Fetch customers error', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Error fetching customers'], 500);
        }
    }

    /**
     * Store a newly created customer in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $authUser = Auth::user();

        if (!$authUser) return response()->json(['message' => 'Unauthorized. Please log in.'], 401);

        if (!in_array($authUser->role, ['admin', 'manager'])) {
            return response()->json(['success' => false, 'message' => 'Access denied.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20|unique:customers,phone_number',
            'email' => 'nullable|email|max:255|unique:customers,email',
            'sex' => 'nullable|in:male,female',

            // Address fields
            'address' => 'nullable|array',
            'address.street' => 'nullable|string',
            'address.city' => 'nullable|string',
            'address.state' => 'nullable|string',
            'address.zip_code' => 'nullable|string',
            'address.country' => 'nullable|string',
            'address.latitude' => 'nullable|numeric',
            'address.longitude' => 'nullable|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            DB::beginTransaction();
            // Create address if address data is provided
            $addressId = null;

            if (
                $request->filled('street') || $request->filled('city') ||
                $request->filled('state') || $request->filled('zip_code') ||
                $request->filled('country') || $request->filled('latitude') ||
                $request->filled('longitude')
            ) {

                $address = new Address();
                $address->street = $request->street;
                $address->city = $request->city;
                $address->state = $request->state;
                $address->zip_code = $request->zip_code;
                $address->country = $request->country;
                $address->latitude = $request->latitude;
                $address->longitude = $request->longitude;
                $address->save();

                $addressId = $address->id;
            }

            $customer = new Customer();
            $customer->first_name = $request->first_name;
            $customer->last_name = $request->last_name;
            $customer->phone_number = $request->phone_number;
            $customer->email = $request->email;
            $customer->sex = $request->sex ?? 'male';
            $customer->address_id = $addressId;
            $customer->added_by = Auth::id(); // Assuming authentication is set up
            $customer->save();

            // Load the address relation if it exists
            if ($addressId) {
                $customer->load('address');
            }
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Customer created successfully',
                'data' => $customer
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Customer store error', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to add customer'], 500);
        }
    }

    /**
     * Display the specified customer.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $authUser = Auth::user();
        if (!$authUser) return response()->json(['message' => 'Unauthorized.'], 401);

        try {
            $customer = Customer::with('address')->find($id);
            return $customer
                ? response()->json(['success' => true, 'data' => $customer])
                : response()->json(['success' => false, 'message' => 'Customer not found'], 404);
        } catch (Exception $e) {
            Log::error('Show customer error', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Error retrieving customer'], 500);
        }
    }

    /**
     * Update the specified customer in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $authUser = Auth::user();
        if (!$authUser) return response()->json(['message' => 'Unauthorized.'], 401);

        if (!in_array($authUser->role, ['admin', 'manager'])) {
            return response()->json(['success' => false, 'message' => 'Access denied.'], 403);
        }

        try {
            DB::beginTransaction();

            $customer = Customer::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'first_name' => 'sometimes|string|max:255',
                'last_name' => 'sometimes|string|max:255',
                'phone_number' => 'sometimes|string|max:20|unique:customers,phone_number,' . $id,
                'email' => 'nullable|email|max:255|unique:customers,email,' . $id,
                'sex' => 'nullable|in:male,female',

                // Address fields
                'street' => 'nullable|string',
                'city' => 'nullable|string',
                'state' => 'nullable|string',
                'zip_code' => 'nullable|string',
                'country' => 'nullable|string',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            // Update customer fields
            if ($request->has('first_name')) {
                $customer->first_name = $request->first_name;
            }

            if ($request->has('last_name')) {
                $customer->last_name = $request->last_name;
            }

            if ($request->has('phone_number')) {
                $customer->phone_number = $request->phone_number;
            }

            if ($request->has('email')) {
                $customer->email = $request->email;
            }

            if ($request->has('sex')) {
                $customer->sex = $request->sex;
            }

            // Update or create address if address data is provided
            if (
                $request->filled('street') || $request->filled('city') ||
                $request->filled('state') || $request->filled('zip_code') ||
                $request->filled('country') || $request->filled('latitude') ||
                $request->filled('longitude')
            ) {

                $address = $customer->address ?? new Address();

                if ($request->has('street')) {
                    $address->street = $request->street;
                }

                if ($request->has('city')) {
                    $address->city = $request->city;
                }

                if ($request->has('state')) {
                    $address->state = $request->state;
                }

                if ($request->has('zip_code')) {
                    $address->zip_code = $request->zip_code;
                }

                if ($request->has('country')) {
                    $address->country = $request->country;
                }

                if ($request->has('latitude')) {
                    $address->latitude = $request->latitude;
                }

                if ($request->has('longitude')) {
                    $address->longitude = $request->longitude;
                }

                $address->save();

                $customer->address_id = $address->id;
            }

            $customer->save();
            // Load the address relation
            $customer->load('address');
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Customer updated successfully',
                'data' => $customer
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Customer update error', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Update failed.'], 500);
        }
    }

    public function addAddress(Request $request, $id)
    {
        $authUser = Auth::user();

        if (!$authUser) {
            return response()->json(['message' => 'Unauthorized. Please log in.'], 401);
        }

        if (!in_array($authUser->role, ['admin', 'manager'])) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'street' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $customer = Customer::findOrFail($id);

            // Check if custo$customer already has address
            if ($customer->address) {
                return response()->json([
                    'success' => false,
                    'message' => 'Address already exists. Please update instead.'
                ], 409);
            }

            $address = new Address($request->all());
            $customer->address()->save($address);

            return response()->json([
                'success' => true,
                'message' => 'Address added successfully',
                'data' => $address
            ]);
        } catch (Exception $e) {
            Log::error('Error adding address', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error adding address'
            ], 500);
        }
    }

    public function updateAddress(Request $request, $id)
    {
        $authUser = Auth::user();

        if (!$authUser) {
            return response()->json(['message' => 'Unauthorized. Please log in.'], 401);
        }

        if (!in_array($authUser->role, ['admin', 'manager'])) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'street' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $customer = Customer::findOrFail($id);

            if (!$customer->address) {
                return response()->json([
                    'success' => false,
                    'message' => 'Address not found for this custo$customer.'
                ], 404);
            }

            $customer->address->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Address updated successfully',
                'data' => $customer->address
            ]);
        } catch (Exception $e) {
            Log::error('Error updating address', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error updating address'
            ], 500);
        }
    }

    /**
     * Archive the specified customer.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $authUser = Auth::user();
        if (!$authUser) return response()->json(['message' => 'Unauthorized.'], 401);

        if (!in_array($authUser->role, ['admin', 'manager'])) {
            return response()->json(['success' => false, 'message' => 'Access denied.'], 403);
        }

        try {
            $customer = Customer::find($id);
            if (!$customer) return response()->json(['success' => false, 'message' => 'Customer not found.'], 404);

            $customer->archived = true;
            $customer->save();

            return response()->json(['success' => true, 'message' => 'Customer archived successfully.']);
        } catch (Exception $e) {
            Log::error('Customer archive error', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Archive failed.'], 500);
        }
    }

    /**
     * Restore the specified customer from archive.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $authUser = Auth::user();
        if (!$authUser) return response()->json(['message' => 'Unauthorized.'], 401);

        if (!in_array($authUser->role, ['admin', 'manager'])) {
            return response()->json(['success' => false, 'message' => 'Access denied.'], 403);
        }

        try {
            $customer = Customer::find($id);
            if (!$customer || !$customer->archived) {
                return response()->json(['success' => false, 'message' => 'Customer not found or not archived.'], 404);
            }

            $customer->archived = false;
            $customer->save();

            return response()->json(['success' => true, 'message' => 'Customer restored successfully.']);
        } catch (Exception $e) {
            Log::error('Customer restore error', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Restore failed.'], 500);
        }
    }

    /**
     * Remove the specified customer from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $authUser = Auth::user();
        if (!$authUser) return response()->json(['message' => 'Unauthorized.'], 401);

        if (!in_array($authUser->role, ['admin', 'manager'])) {
            return response()->json(['success' => false, 'message' => 'Access denied.'], 403);
        }

        try {
            $customer = Customer::find($id);
            if (!$customer) return response()->json(['success' => false, 'message' => 'Customer not found.'], 404);

            $customer->delete();
            return response()->json(['success' => true, 'message' => 'Customer deleted successfully.']);
        } catch (Exception $e) {
            Log::error('Customer delete error', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Deletion failed.'], 500);
        }
    }
}
