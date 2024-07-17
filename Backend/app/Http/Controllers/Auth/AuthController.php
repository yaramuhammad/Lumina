<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = User::create($request->validated());
        $token = $user->generateToken();
        event(new Registered($user));

        return response()->json([...$user->toArray(), 'token' => $token]);
    }

    /**
     * @throws ValidationException
     */
    public function login(LoginRequest $request)
    {
        if (auth()->attempt($request->validated())) {

            $user = auth()->user();
            $token = $user->generateToken();

            return response()->json([...$user->toArray(), 'token' => $token]);
        } else {
            throw ValidationException::withMessages([
                'email' => ['Invalid Credentials.'],
            ]);
        }
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json(['message' => 'signed out successfully']);
    }
}
