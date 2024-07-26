<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\User;
use Database\Seeders\AdminSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller

{
    public function register_user(Request $request)
    {

        $RegisterData = $request->validate([
            'name' => 'required |string',
            'avatar' => 'required |string',
            'email' => 'required |unique:users',
            'password' => 'required|min:6',
        ], [
            'email.unique' => 'Phone already exists!!'
        ]);

        $user = User::query()->create([
            'name' => $request->name,
            'avatar' => $request->avatar,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $token = $user->createToken('personal Access Token')->plainTextToken;
        $user['token'] = $token;
        $user->save();

        return response()->json([
            'user' => $user,
            'token' => $token,
            'message' => 'registered successfully'
        ], 201);

    }


////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function login_user(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'exists:users,email'],
            'password' => ['required']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()->toArray(),
                'status' => 422
            ], 422);
        }

        if (!Auth::attempt($request->only(['email', 'password']))) {
            // Authentication failed due to invalid credentials
            return response()->json([
                'message' => 'Invalid email or password',
                'status'=> 401
            ],401);
        }

        $user = User::query()->where('email', '=', $request['email'])->first();
        $token = $user['token'];


        return response()->json([
            'token' => $token,
            'user' => $user,
            'message' => 'User logged in successfully',
            'status'=> 200
        ], 200);
    }


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///
    public function logout_user()
    {
        Auth::user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'user log out successfully'
        ], 200);
    }


//////////////////////////////////////////////////////////////////////////////////////////////////////////
}
