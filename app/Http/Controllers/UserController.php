<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Psy\Util\Json;

class UserController extends Controller
{
    public function index($id): JsonResponse
    {
        $user = User::find($id);
        return $user ? response()->json($user) : response()->json(['error' => 'user not found!']);
    }

    public function indexAll(): JsonResponse
    {
        $users = User::all();
        return response()->json($users);
    }

    public function users(): JsonResponse
    {
        
        if (request()->has('q')) {
            $users = User::where('first_name', 'like', '%' . request()->get('q') . '%')
            ->orWhere('last_name', 'like', '%' . request()->get('q') . '%')
            ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', '%' . request()->get('q') . '%')
            ->get();

            if ($users->isEmpty()) {
                return response()->json(['message' => 'not found!'], 404);
            } else {
                return response()->json(['count' => $users->count(), 'data' => $users->toQuery()->orderByDesc('created_at')->paginate(10)]);
            }
        }
        $users = User::all();
        return response()->json(['data' => $users->toQuery()->orderByDesc('created_at')->paginate(10)]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'first_name' => 'required|string|min:3|max:255',
            'last_name' => 'required|string|min:3|max:255',
            'username' => 'required|string|unique:users',
            'role' => 'required|string|in:doctor,nurse,administrator,pharmacist',
            'password' => 'required|string|min:6',
        ]);
        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);
        return response()->json($user);
    }

    public function update(Request $request, $id): JsonResponse
    {


        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'user not found!']);
        }
        $data = $request->validate([
            'first_name' => 'nullable|string|min:3|max:255',
            'last_name' => 'nullable|string|min:3|max:255',
            'username' => 'nullable|string|unique:users,username,' . $user->id,
            'role' => 'nullable|string|in:doctor,nurse,administrator,pharmacist',
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
