<?php

namespace App\Services;

use App\Models\Vendor;
use Illuminate\Support\Facades\Hash;

class AuthService 
{
    public function register(array $data):array
    {
        $vendor = Vendor::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'business_name' => $data['business_name'] ?? null,
            'phone' => $data['phone'] ?? null,

        ]);
        $token = $vendor->createToken('auth_token')->plainTextToken;

        return [
            'vendor' => $vendor,
            'token' => $token,
        ];
    }

    public function login(array $data):array
    {
        $vendor = Vendor::where('email', $data['email'])->first();

        if (!$vendor || !Hash::check($data['password'], $vendor->password)) {
            throw new \Exception('Invalid credentials');
        }

        $vendor->tokens()->delete();
        $token = $vendor->createToken('api_token')->plainTextToken;

        return [
            'vendor' => $vendor,
            'token' => $token,
        ];
    }

    public function logout(Vendor $vendor):void
    {
        $vendor->tokens()->delete();
    }

}