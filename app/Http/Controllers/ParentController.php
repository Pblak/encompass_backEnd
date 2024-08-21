<?php

namespace App\Http\Controllers;

use App\Models\Parents;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParentController extends Controller
{
    public function getParents(Request $request): JsonResponse
    {
        return response()->json(Parents::with(['students'])->get());
    }
    public function getParent(Request $request, $id): JsonResponse
    {
        return response()->json(Parents::find($id));
    }
    public function updateParent(Request $request, $id): JsonResponse
    {
        $parent = Parents::find($id);
        $parent->update($request->all());
        return response()->json($parent);
    }
    public function createParent(Request $request): JsonResponse
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:parents,email',
            'password' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $parent = Parents::create($request->all());
            DB::commit();
            return response()->json([
                "result" => $parent,
                "message" => "Parent created successfully",
                "_t" => "success",
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }
    public function deleteParent(Request $request, $id)
    {
        $parent = Parents::find($id);
        $parent->delete();
        return response()->json($parent);
    }

}
