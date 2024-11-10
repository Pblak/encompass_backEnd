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
        $relations = [
            'students',
        ];
        $parents = $request->withTrashed === "true" ?
            Parents::withTrashed()->with($relations)->get() :
            Parents::with($relations)->get();

        return response()->json($parents);
    }

    public function getParent(Request $request, $id): JsonResponse
    {
        return response()->json(Parents::find($id));
    }

    public function updateParent(Request $request): JsonResponse
    {
       DB::beginTransaction();
        try {
            $request->validate([
                'id' => 'required|exists:parents,id',
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'nullable|email|unique:parents,email,' . $request->id,
            ]);
            $parent = Parents::find($request->id);
            $parent->update($request->all());
            DB::commit();
            return response()->json([
                'result' => $parent,
                'message' => 'Parent updated successfully',
                '_t' => 'success',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage(),
                '_t' => 'error',
            ], 500);
        }
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

    public function deleteParent(Request $request): JsonResponse
    {
//        dd($request);
        DB::beginTransaction();
        try {
            $request->validate([
                'id' => 'required|exists:parents,id',
            ]);
            $parent = Parents::find($request->id);
            if ($parent->deleted_at) {
                DB::commit();
                return response()->json([
                    'message' => 'Parent already deleted',
                    '_t' => 'warning',
                ]);
            }
            $parent->delete();
            DB::commit();
            return response()->json([
                'message' => 'Parent deleted successfully',
                '_t' => 'success',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Parent not deleted',
                '_t' => 'error',
            ], 500);
        }
    }

}
