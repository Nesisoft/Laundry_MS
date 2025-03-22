<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    /**
     * Display a listing of the services.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Service::query();

        // Apply filters if provided
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->has('archived')) {
            $query->where('archived', $request->boolean('archived'));
        } else {
            $query->where('archived', false); // Default: show non-archived
        }

        // Pagination
        $perPage = $request->input('per_page', 15);
        $services = $query->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => $services
        ]);
    }

    /**
     * Store a newly created service in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $service = new Service();
        $service->name = $request->name;
        $service->description = $request->description;
        $service->added_by = Auth::id(); // Assuming authentication is set up
        $service->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Service created successfully',
            'data' => $service
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified service.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $service = Service::findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $service
        ]);
    }

    /**
     * Update the specified service in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($request->has('name')) {
            $service->name = $request->name;
        }

        if ($request->has('description')) {
            $service->description = $request->description;
        }

        $service->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Service updated successfully',
            'data' => $service
        ]);
    }

    /**
     * Archive the specified service.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $service = Service::findOrFail($id);
        $service->archived = true;
        $service->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Service archived successfully'
        ]);
    }

    /**
     * Restore the specified service from archive.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $service = Service::findOrFail($id);
        $service->archived = false;
        $service->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Service restored successfully'
        ]);
    }

    /**
     * Remove the specified service from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Service deleted successfully'
        ]);
    }
}
