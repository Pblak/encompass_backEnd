<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class TeacherController extends Controller
{

    public function getTeachers(Request $request)
    {
        return response()->json(Teacher::with(['instruments'])->get());
    }

    public function getTeacher(Request $request, $id)
    {
        return response()->json(Teacher::find($id));
    }

    public function updateTeacher(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'id' => 'required|exists:teachers,id',
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'nullable|email|unique:teachers,email,' . $request->id,
            ]);
            // remove name from the request
            $updateData = (object)$request->except(['avatar','name']);

            if ($request->file('infos.avatar')) {
                $imageFile = $request->file('infos.avatar');
                $path = 'images/teachers/' . $request->id . '/avatar.' . $imageFile->getClientOriginalExtension();
                Storage::disk('public')->put($path, file_get_contents($imageFile->getRealPath()));
                $updateData->infos['avatar'] = 'storage/' . $path;
            }

            $teacher = Teacher::find($request->id);
            $teacher->update((array)$updateData);
            DB::commit();
            return response()->json([
                "result" => $teacher,
                "message" => "Teacher updated successfully",
                "_t" => "success",
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function createTeacher(Request $request)
    {
        DB::beginTransaction();
        try {
            $teacher = Teacher::create([
                ...$request->all(),
                'password' => Hash::make($request->password),
                'type' => 'teacher'
            ]);

            DB::commit();
            return response()->json([
                "result" => $teacher,
                "message" => "Teacher created successfully",
                "_t" => "success",
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
