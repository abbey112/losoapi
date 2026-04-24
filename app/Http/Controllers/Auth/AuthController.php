<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\AuthService;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthController extends Controller
{
    public function __construct(private readonly AuthService $authService) {}

    public function register(RegisterRequest $request):JsonResponse
    {
        $vendor = $this->authService->register($request->validated());
        return response()->json([
            'message' => 'Vendor registered successfully',
            'vendor' => $vendor
            ], 201);
    }

    public function login(LoginRequest $request):JsonResponse
    {
        $result = $this->authService->login($request->validated());

        if (!$result) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }
        return response()->json([
            'message' => 'Login successful',
            'data' => $result
        ], 200);
    }

    public function logout(Request $request):JsonResponse
    {
        $this->authService->logout($request->user());
        return response()->json([
            'message' => 'Logout successful'
        ], 200);
    }

}
