<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ConfigController extends Controller
{
    /**
     * Verify Access Token
     */
    public function index(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'product_key' => 'required|string|min:16|max:16',
        ]);

        if ($validator->fails()) {
            Log::info("Access token verification failed");
            return response()->json([
                'message' => 'Access token verification failed',
                'data' => $validator->errors()
            ], 422);
        }

        //make a request to an endpoint with the product key

        //Insert business information details receive

        return response()->json([
            'message' => 'Access token verified successfully',
            'data' => $validator->validated()
        ], 200);
    }
}
