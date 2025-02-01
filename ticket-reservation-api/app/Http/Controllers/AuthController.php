<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    // Kullanıcı kayıt endpointi
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
                'tel_no' => 'required|string|max:15|unique:users',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'tel_no' => $request->tel_no,
            ]);

            return response()->json(['message' => 'User registered successfully'], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Validation failed', 'messages' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Server error', 'message' => $e->getMessage()], 500);
        }
    }

    // Kullanıcı giriş endpointi
    public function login(Request $request)
    {
        try {
            // Giriş için gerekli verileri al
            $credentials = $request->only('email', 'password');

            if (!$token = auth()->guard('api')->attempt($credentials)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            return response()->json([
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Server error', 'message' => $e->getMessage()], 500);
        }
    }

    // Token yenileme endpointi
    public function refresh()
    {
        try {
            $oldtoken = auth()->getToken();
            $newToken = auth()->refresh();
            // Eski token'ı geçersiz kıl
            auth()->invalidate($oldtoken);
            return response()->json([
                'access_token' => $newToken,
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Token refresh failed', 'message' => $e->getMessage()], 500);
        }
    }

    // Kullanıcı çıkış endpointi
    public function logout()
    {
        try {
            $token = auth()->getToken();
            auth()->invalidate($token);

            return response()->json(['message' => 'Successfully logged out'], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Logout failed'], 500);
        }
    }


    // Token bilgilerini döndürme fonksiyonu
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ], 200);
    }
}