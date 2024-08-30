<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PackageController extends Controller
{
    public function getPackages(): JsonResponse
    {
        return response()->json(Package::all(), 200);
    }

    public function getPackage($id): JsonResponse
    {
        $package = Package::find($id);
        if (is_null($package)) {
            return response()->json(['message' => 'Package not found'], 404);
        }
        return response()->json($package::find($id), 200);
    }

    public function createPackage(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required',
            'duration' => 'required|integer',
            'description' => 'max:255',
        ]);
        DB::beginTransaction();
        try {
            Package::create($request->all());
            DB::commit();
            return response()->json([
                'message' => 'Package created successfully',
                '_t' => "success",
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Package creation failed!',
                '_t' => "error",
            ], 409);
        }
    }

    public function updatePackage(Request $request): JsonResponse
    {
        $request->validate([
            'id' => 'required|exists:packages,id',
            'name' => 'required',
            'price' => 'required',
            'duration' => 'required|integer',
            'description' => 'max:255',
        ]);
        DB::beginTransaction();
        try {
            $package = Package::find($request->id);
            $package->update($request->all());
            DB::commit();
            return response()->json([
                'message' => 'Package updated successfully',
                '_t' => "success",
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Package update failed!',
                '_t' => "error",
            ], 409);
        }
    }
}
