<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;

class TeacherController extends Controller
{

    public function getTeachers(Request $request)
    {
        return response()->json(User::where('type', 'teacher')->get());
    }

    public function getTeacher(Request $request, $id)
    {
        return response()->json(User::find($id));
    }

    public function updateTeacher(Request $request, $id)
    {
        $teacher = User::find($id);
        $teacher->update($request->all());
        return response()->json($teacher);
    }

    public function createTeacher(Request $request)
    {
        $teacher = User::create($request->all());
        return response()->json($teacher);
    }

}
