<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
    public function __construct()
    {
        // check user has token (logged in) to update and destroy
        $this->middleware('auth:api')->only(['update', 'destroy']);
    }

    public function show($user)
    {
        return Cache::tags('users')->remember("user-{$user}", now()->addMinutes(5), function () use ($user) {
            return new UserResource(User::with('books')->findOrFail($user));
        });
    }

    public function update(UserUpdateRequest $request, User $user)
    {
        // update user record with request's data
        $user->update($request->all());
        // return successful message
        return response()->json([
            'message' => 'Update successfully',
            'data' => new UserResource($user)
        ]);
    }

    public function destroy(User $user)
    {
        // delete user's tokens
        $user->tokens()->delete();
        // delete user record
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
