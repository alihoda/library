<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserAuthController extends Controller
{
    public function register(UserRegisterRequest $request)
    {
        $request->validated();

        // hash password
        $request['password'] = Hash::make($request['password']);
        // create user
        $user = User::create($request->all());
        // check user is created
        if (is_null($user)) {
            return response()->json(['message' => 'Something went wrong !!'], 422);
        }
        // return user
        return response()->json([
            'message' => 'User registered successfully',
            'data' => new UserResource($user)
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        // try to authenticate user
        if (!Auth::attempt($request->only(['username', 'password']))) {
            return response()->json(['message' => 'Invalid credentials'], 422);
        }
        // retrieve user based on username
        $user = User::where('username', $request['username'])->first();
        // delete previous token if user is already logged in
        $user->tokens()->delete();
        // create token
        $token = $user->createToken('auth')->plainTextToken;

        return response()->json([
            'message' => 'Logged in',
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        // delete requested user's tokens
        $request->user()->tokens()->delete();
        // return message
        return response()->json(['message' => 'Logged out successfully']);
    }
}
