<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\MedicalRecord;
use App\Models\Observation;
use App\Models\Patient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageController extends Controller
{
    public function index(Patient $patient, MedicalRecord $medicalRecord, Observation $observation): JsonResponse
    {
        $this->authorize('belongings', [Image::class,$patient , $medicalRecord, $observation]);
        $images = $observation->images;
        $data = [];
        foreach ($images as $image) {
            $data[] = $this->add_abilities($image , $medicalRecord , $observation );
        }
        return response()->json(['data' => $images]);
    }

    public function store(Request $request ,Patient $patient, MedicalRecord $medicalRecord, Observation $observation, )
    {
        $this->authorize('create', [Image::class, $patient, $medicalRecord, $observation]);

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif',
        ]);

        $imagePath = $request->file('image')->store('public/images');
        $imageName = Str::random(40) . '.' . $request->file('image')->getClientOriginalExtension();

        $image = $observation->images()->create([
            'path' => $imageName
        ]);

        Storage::move($imagePath, 'public/images/' . $imageName);

        $data = [
            'image' => $image,
            'can_delete' => true,
        ];

        return response()->json(['data' => $data]);
    }
    public function delete(Patient $patient, MedicalRecord $medicalRecord, Observation $observation, Image $image): JsonResponse
    {
        $this->authorize('belongings', [Image::class,$patient , $medicalRecord, $observation]);

        $this->authorize('delete', [$image, $medicalRecord , $observation]);
        Storage::delete($image->path);
        $image->delete();
        return response()->json(['message' => 'image deleted successfully.']);
    }

    private function add_abilities($data, $medicalRecord, $observation) {
        $data['can_delete'] = Auth::user()->can('delete', [$data, $medicalRecord, $observation]);
        return $data;
    }
}
