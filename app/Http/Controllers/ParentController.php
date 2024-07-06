<?php

namespace App\Http\Controllers;

use App\Models\Parents;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
        $parent = Parents::create($request->all());
        return response()->json($parent);
    }
    public function deleteParent(Request $request, $id)
    {
        $parent = Parents::find($id);
        $parent->delete();
        return response()->json($parent);
    }

}
