<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
            $teacher = Teacher::find($request->id);
            $teacher->update($request->all());
            DB::commit();
            return response()->json($teacher);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function createTeacher(Request $request)
    {
       DB::beginTransaction();
        try {
            $teacher= Teacher::create([
                ...$request->all(),
                'password' => Hash::make($request->password),
                'type' => 'teacher'
            ]);

            DB::commit();
            return response()->json($teacher);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
