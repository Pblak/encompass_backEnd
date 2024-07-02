<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;

class TeacherController extends Controller
{

    public function getTeachers(Request $request)
    {
        return response()->json(Teacher::all());
    }

    public function getTeacher(Request $request, $id)
    {
        return response()->json(Teacher::find($id));
    }

    public function updateTeacher(Request $request, $id)
    {
        $teacher = Teacher::find($id);
        $teacher->update($request->all());
        return response()->json($teacher);
    }

    public function createTeacher(Request $request)
    {
        $teacher = Teacher::create($request->all());
        return response()->json($teacher);
    }

}
