<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Auth\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService)
    {
    }
    
    public function register(Request $request)
    {
        $user = $this->authService->register($request);
        
    }

    public function login(Request $request)
    {
        $response = $this->authService->login($request);

        if ($response) {
            return response()->json([
                'status' => 'success',
                'message' => 'User logged in successfully',
                'data' => $response,
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Invalid credentials',
        ], 401);
    }

    public function logout()
    {
        $response = $this->authService->logout();

        return response()->json([
            'status' => 'success',
            'message' => 'User logged out successfully',
            'data' => $response,
        ]);
    }
}
