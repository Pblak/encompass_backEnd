<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{

    public function getStudents(Request $request)
    {
        return response()->json(Student::all());
    }

    public function getStudent(Request $request, $id)
    {
        return response()->json(Student::find($id));
    }

    public function updateStudent(Request $request, $id)
    {
        $student = Student::find($id);
        $student->update($request->all());
        return response()->json($student);
    }

    public function createStudent(Request $request)
    {
        $student = Student::create($request->all());
        return response()->json($student);
    }

    public function deleteStudent(Request $request, $id)
    {
        $student = Student::find($id);
        $student->delete();
        return response()->json($student);
    }

}
