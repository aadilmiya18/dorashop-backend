<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRegisterRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\UserResource;
use App\Models\Customer;
use App\Models\User;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class CustomerAuthController extends Controller
{
    public function registerUser(CustomerRegisterRequest $request)
    {
        $user = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => Hash::make($request->input('password'))
        ]);

        $token = $user->createToken('customer-dora-shop-token')->plainTextToken;

        return response()->json([
            'message' => 'Registered successfully',
            'user' => new CustomerResource($user),
            'token' => $token
        ], 201);
    }

    public function loginUser(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required'
        ]);

        $user = Customer::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => 'The provided credentials are invalid.'
            ]);
        }

        $token = $user->createToken('customer-dora-shop-token')->plainTextToken;

        return response()->json([
            'message' => 'Logged in successfully',
            'user' => new CustomerResource($user),
            'token' => $token
        ], 200);
    }

    public function logoutUser(Request $request)
    {
        $user = $request->user();

        $user->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Customer logout successfully'
        ]);

    }

    public function me(Request $request)
    {
        return response()->json([
            'user' => new CustomerResource($request->user())
        ]);
    }

}
