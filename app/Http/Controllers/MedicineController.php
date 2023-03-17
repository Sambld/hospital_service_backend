<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicineController extends Controller
{
    public function medicine(  Medicine $medicine): JsonResponse
    {
//        $this->authorize('view', $medicine);
        return response()->json(['data' => $medicine]);
    }

    public function index(Request $request): JsonResponse
    {
//        $this->authorize('viewAny', Medicine::class);
        if ($request->has('q')) {
            return $this->search($request);
        }
        $medicines = Medicine::paginate(20);
        return response()->json(['data' => $medicines]);
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', Medicine::class);
        $data = $request->validate([
            'name' => 'required|string',
            'category' => 'required|string',
            'description' => 'required|string',
            'quantity' => 'required|integer',
            'is_pharmaceutical' => 'required|boolean',
            'manufacturer' => 'nullable|string',
            'supplier' => 'nullable|string',
            'expiration_date' => 'required|date',
        ]);

        $medicine = Medicine::create($data);
//        $medicine = $this->add_abilities($medicine);
        return response()->json(['data' => $medicine]);
    }

    public function update(Request $request, Medicine $medicine): JsonResponse
    {
        $this->authorize('update', $medicine);
        $data = $request->validate([
            'name' => 'string',
            'category' => 'string',
            'description' => 'string',
            'price' => 'nullable|numeric',
            'quantity' => 'integer',
            'is_pharmaceutical' => 'boolean',
            'manufacturer' => 'nullable|string',
            'supplier' => 'nullable|string',
            'expiration_date' => 'date',
        ]);

        $medicine->update($data);
//        $medicine = $this->add_abilities($medicine);
        return response()->json(['data' => $medicine]);
    }

    public function delete(Medicine $medicine): JsonResponse
    {
        $this->authorize('delete', $medicine);
        $medicine->delete();
        return response()->json(['message' => 'Medicine deleted successfully.']);
    }

    private function add_abilities($medicine)
    {
        $medicine['can_update'] = Auth::user()->can('update', $medicine);
        $medicine['can_delete'] = Auth::user()->can('delete', $medicine);
        return $medicine;
    }

    public function search(Request $request)
    {
        $searchTerm = $request->input('q');
        $medicines = Medicine::where('name', 'LIKE', "%{$searchTerm}%")
            ->orWhere('category', 'LIKE', "%{$searchTerm}%")
            ->orWhere('description', 'LIKE', "%{$searchTerm}%")
            ->paginate();



        return response()->json(['data' => $medicines]);
    }
}
