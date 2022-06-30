<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $token = $user->createToken('MyToken')->plainTextToken;

        return response()->json([
            'api_status' => 200,
            'message' => 'Success registering user',
            'token' => $token,
            'data' => $user
        ]);
    }
    /**
     * Login user and create token
     */
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }
        $token = $user->createToken('MyToken')->plainTextToken;
        return response()->json([
            'api_status' => 200,
            'message' => 'Successfully logged in',
            'token' => $token,
            'user' => $user
        ], 200);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $user =  $request->user();
        $user->currentAccessToken()->delete();
        return response()->json([
            'api_status' => 200,
            'message' => 'Successfully logged out'
        ], 200);
    }
}
