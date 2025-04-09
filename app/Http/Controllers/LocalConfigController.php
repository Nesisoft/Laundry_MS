<?php

namespace App\Http\Controllers;

use App\Models\LocalConfig;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

final class LocalConfigController extends Controller
{
    /**
     * Fetch all configurations.
     */
    public function fetchAll(): JsonResponse
    {
        // Ensure the user is authenticated
        $authUser = Auth::user();

        if (!$authUser) {
            return response()->json(['message' => 'Unauthorized. Please log in.'], 401);
        }

        try {
            $localConfigs = LocalConfig::all();
            return response()->json(['success' => true, 'data' => $localConfigs], 200);
        } catch (Exception $e) {
            Log::error('Error fetching localConfigs', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Error fetching configurations.'], 500);
        }
    }

    /**
     * Fetch a specific config by key.
     */
    public function fetchOne(string $key): JsonResponse
    {
        // Ensure the user is authenticated
        $authUser = Auth::user();

        if (!$authUser) {
            return response()->json(['message' => 'Unauthorized. Please log in.'], 401);
        }

        $config = LocalConfig::where('key', $key)->first();

        if (!$config) {
            return response()->json(['success' => false, 'message' => 'Configuration not found.'], 404);
        }

        return response()->json(['success' => true, 'data' => $config], 200);
    }

    /**
     * Create a new configuration entry.
     */
    public function add(Request $request): JsonResponse
    {
        // Ensure the user is authenticated
        $authUser = Auth::user();

        if (!$authUser) {
            return response()->json(['message' => 'Unauthorized. Please log in.'], 401);
        }

        // Ensure the authenticated user is an admin
        if (!in_array($authUser->role, ['admin', 'manager'])) {
            return response()->json(['message' => 'Access denied.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'key' => 'required|string|unique:local_configs,key|max:255',
            'value' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $config = LocalConfig::create($request->only(['key', 'value']));
            return response()->json(['success' => true, 'message' => 'Configuration added successfully.', 'data' => $config], 201);
        } catch (Exception $e) {
            Log::error('Error adding config', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Error adding configuration.'], 500);
        }
    }

    // set values for all default keys
    public function setAllConfigValues(Request $request): JsonResponse
    {
        $values = $request->all();

        // Ensure the user is authenticated
        $authUser = Auth::user();

        if (!$authUser) {
            return response()->json(['message' => 'Unauthorized. Please log in.'], 401);
        }

        // Ensure the authenticated user is an admin
        if (!in_array($authUser->role, ['admin', 'manager'])) {
            return response()->json(['message' => 'Access denied.'], 403);
        }

        try {
            // Define the default configuration keys
            $defaultKeys = [
                'business_name',
                'branch_name',
                'phone_number',
                'email',
                'motto'
            ];

            DB::beginTransaction();

            foreach ($defaultKeys as $key) {
                // Ensure only provided values are updated
                $value = $values[$key] ?? null;

                DB::table('local_configs')->updateOrInsert(
                    ['key' => $key],
                    ['value' => $value, 'updated_at' => now()]
                );
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Configurations updated successfully.'
            ], 200);
        } catch (Exception $e) {
            DB::rollback();
            Log::error('Error updating localConfigs', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating configurations.'
            ], 500);
        }
    }

    // reset default key values
    public function resetDefaultConfigs(): JsonResponse
    {
        // Ensure the user is authenticated
        $authUser = Auth::user();

        if (!$authUser) {
            return response()->json(['message' => 'Unauthorized. Please log in.'], 401);
        }

        // Ensure the authenticated user is an admin
        if (!in_array($authUser->role, ['admin', 'manager'])) {
            return response()->json(['message' => 'Access denied.'], 403);
        }

        try {
            // Define the default configuration keys
            $defaultData = [
                'business_name' => NULL,
                'branch_name' => NULL,
                'phone_number' => NULL,
                'email' => NULL,
                'logo' => NULL,
                'banner' => NULL,
                'motto' => NULL
            ];

            DB::beginTransaction();

            foreach ($defaultData as $key => $value) {
                // Update if the key exists, otherwise insert
                DB::table('local_configs')->updateOrInsert(
                    ['key' => $key],
                    ['value' => $value, 'updated_at' => now()]
                );
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'All configurations have been reset to default.'
            ], 200);
        } catch (Exception $e) {
            DB::rollback();
            Log::error('Error resetting default localConfigs', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while resetting configurations.'
            ], 500);
        }
    }

    public function setLogo(Request $request): JsonResponse
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'logo' => 'required|image|mimes:png,jpg,jpeg|max:2048' // Max 2MB
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid logo file. Only PNG, JPG, or JPEG files are allowed.',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Ensure the directory exists
            $storagePath = storage_path('app/public/uploads/logos');
            if (!File::exists($storagePath)) {
                File::makeDirectory($storagePath, 0755, true, true);
            }

            // Store the uploaded file
            $file = $request->file('logo');
            $fileName = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = 'uploads/logos/' . $fileName; // Relative to storage/app/public
            $file->storeAs('public/' . $filePath); // Saves in storage/app/public/uploads/logos

            // Update the config value for logo
            LocalConfig::updateOrCreate(['key' => 'logo'], ['value' => $filePath]);

            return response()->json([
                'success' => true,
                'message' => 'Logo uploaded successfully.',
                'logo_url' => asset('storage/' . $filePath)
            ], 200);
        } catch (Exception $e) {
            Log::error('Error uploading logo', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while uploading the logo.'
            ], 500);
        }
    }

    /**
     * Update an existing configuration.
     */
    public function update(Request $request): JsonResponse
    {
        // Ensure the user is authenticated
        $authUser = Auth::user();

        if (!$authUser) {
            return response()->json(['message' => 'Unauthorized. Please log in.'], 401);
        }

        // Ensure the authenticated user is an admin
        if (!in_array($authUser->role, ['admin', 'manager'])) {
            return response()->json(['message' => 'Access denied.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'key' => 'required|string|exists:local_configs,key|max:255',
            'value' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $config = LocalConfig::where('key', $request->key)->first();

        if (!$config) {
            return response()->json(['success' => false, 'message' => 'Configuration not found.'], 404);
        }

        try {
            $config->update(['value' => $request->value]);
            return response()->json(['success' => true, 'message' => 'Configuration updated successfully.', 'data' => $config], 200);
        } catch (Exception $e) {
            Log::error('Error updating config', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Error updating configuration.'], 500);
        }
    }

    /**
     * Delete a configuration by key.
     */
    public function delete(string $key): JsonResponse
    {
        // Ensure the user is authenticated
        $authUser = Auth::user();

        if (!$authUser) {
            return response()->json(['message' => 'Unauthorized. Please log in.'], 401);
        }

        // Ensure the authenticated user is an admin
        if (!in_array($authUser->role, ['admin', 'manager'])) {
            return response()->json(['message' => 'Access denied.'], 403);
        }

        $config = LocalConfig::where('key', $key)->first();

        if (!$config) {
            return response()->json(['success' => false, 'message' => 'Configuration not found.'], 404);
        }

        try {
            $config->delete();
            return response()->json(['success' => true, 'message' => 'Configuration deleted successfully.'], 200);
        } catch (Exception $e) {
            Log::error('Error deleting config', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Error deleting configuration.'], 500);
        }
    }

    /**
     * Get a configuration value by key.
     */
    public function getValue(string $key): JsonResponse
    {
        // Ensure the user is authenticated
        $authUser = Auth::user();

        if (!$authUser) {
            return response()->json(['message' => 'Unauthorized. Please log in.'], 401);
        }

        $config = LocalConfig::where('key', $key)->value('value');

        if ($config === null) {
            return response()->json(['success' => false, 'message' => 'Configuration not found.'], 404);
        }

        return response()->json(['success' => true, 'value' => $config], 200);
    }
}
