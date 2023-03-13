<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Psy\Util\Json;

class UserController extends Controller
{
    public function index($id): JsonResponse
    {
        $user = User::find($id);
        return $user ? response()->json($user) : response()->json(['error' => 'user not found!']);
    }

    public function update(Request $request, $id): JsonResponse
    {


        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'user not found!']);
        }
        $data = $request->validate([
            'first_name' => 'required|string|min:3|max:255',
            'last_name' => 'required|string|min:3|max:255',
            'username' => 'required|string|unique:users,username,' . $user->id,
            'role' => 'required|string|in:doctor,nurse,administrator',
            'password' => 'nullable|string|min:6',
        ]);
        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }


        $user->update($data);

        return response()->json([$user]);

    }

    public function delete($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'user not found!']);
        } else {
            $user->tokens()->delete();
            $user->delete();
            return response()->json(['message' => 'user deleted successfully!']);
        }
    }

    public function self(): JsonResponse
    {
        return response()->json(auth()->user());
    }
}
