<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //
    function VerifyAccessToken(Request $request) : JsonResponse {
        $validator = Validator::make($request->all(), [
            'access_token' => 'required|string|min:16|max:16|exists:businesses,access_token',
        ]);
        
        if ($validator->fails()) {
            Log::info("Access token verification failed");
            return response()->json([
                'message' => 'Access token verification failed',
                'data' => $validator->errors()
            ], 422);
        }
        
    }
}
