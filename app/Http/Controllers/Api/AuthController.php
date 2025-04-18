<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Responses\ApiResponse;
use App\Services\Auth\AuthService;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService) {}

    public function register(RegisterRequest $request)
    {
        $user = $this->authService->register($request);

        return ApiResponse::success([
            'user' => $user,
            'alert' => true
        ], 'User registered successfully');
    }

    public function login(LoginRequest $request)
    {
        $response = $this->authService->login($request);

        if ($response) {
            return ApiResponse::success($response['user'], 'User logged in successfully')
                ->cookie($response['cookie']);
        }

        return ApiResponse::error('Invalid credentials', 401, ['alert' => true]);
    }

    public function logout()
    {
        $response = $this->authService->logout();

        return ApiResponse::success(['alert' => true], 'User logged out successfully')
            ->cookie($response['cookie']);
    }

    public function user()
    {
        $user = $this->authService->user();

        return ApiResponse::success($user, 'User retrieved successfully');
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $sendLink = $this->authService->forgotPassword($request);

        if ($sendLink['status'] === 'error') {
            return ApiResponse::error($sendLink['message'], 422, ['alert' => true]);
        }

        if ($sendLink['status'] === 'success') {
            return ApiResponse::success(['alert' => true], $sendLink['message']);
        }
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $reset = $this->authService->resetPassword($request);

        if ($reset['status'] === 'error') {
            return ApiResponse::error($reset['message'], 422, ['alert' => true]);
        }

        if ($reset['status'] === 'success') {
            return ApiResponse::success(['alert' => true], $reset['message']);
        }
    }
}
