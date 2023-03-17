<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Handle an incoming authentication request.
     *
     * @param Request $request
     * @return JsonResponse
     */


    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required:string',
            'password' => 'required:string',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    public function register(Request $request) : JsonResponse
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'username' => 'required|string|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|string|in:doctor,nurse,administrator,pharmacist',
        ]);

        $user = new User([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'role' => $request->role,
        ]);
        $user->save();

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'user' => $user->makeHidden('password'),
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);

    }

    /**
     * Handle an incoming logout request.
     *
     * @param Request $request
     * @return JsonResponse
     */


    public function logout(Request $request): JsonResponse
    {
        if (Auth::check()){
            auth()->user()->currentAccessToken()->delete();
            return response()->json(['response'=>'Logged out successfully'] , 201);
        }else {
            return response()->json(['msg'=>'not logged in']);
        }
//        $request->user()->tokens()->delete();
//
//        return response()->json(['message' => 'Logout successful']);
    }
}
