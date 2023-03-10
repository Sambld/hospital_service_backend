<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class StaffController extends Controller
{
    public function register(Request $request) : JsonResponse
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'username' => 'required|string|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|string|in:doctor,nurse,administrator',
        ]);

        $staff = new Staff([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'role' => $request->role,
        ]);
        $staff->save();

        $token = $staff->createToken('authToken')->plainTextToken;

        return response()->json([
            'user' => $staff->makeHidden('password'),
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);

    }

    public function login(Request $request) : JsonResponse
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            throw ValidationException::withMessages([
                'username' => ['The provided credentials are incorrect.'],
            ]);
        }

        $staff = $request->user();
        $token = $staff->createToken('authToken')->plainTextToken;

        return response()->json([
            'staff' => $staff,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }



    public function delete(Request $request, $id): JsonResponse
    {
        $staff = Staff::find($id);

        if (!$staff) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

//        if ($staff->id != Auth::user()->id && Auth::user()->role != 'administrator') {
//            return response()->json([
//                'message' => 'Unauthorized',
//            ], 403);
//        }

        $staff->delete();

        return response()->json([
            'message' => 'User deleted successfully',
        ]);
    }

}
