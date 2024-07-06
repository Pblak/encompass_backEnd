<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{

    public function getStudents(Request $request): JsonResponse
    {
        return response()->json(Student::with(['instruments' ,'parent'])->get());
    }

    public function getStudent(Request $request, $id): JsonResponse
    {
        return response()->json(Student::with(['instruments'])->find($id));
    }

    public function updateStudent(Request $request, $id): JsonResponse
    {
        $student = Student::find($id);
        $student->update($request->all());
        return response()->json($student);
    }

    public function createStudent(Request $request): JsonResponse
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:students,email',
            'password' => 'required',
            'parent_id' => 'required|exists:users,id',
        ]);
        DB::beginTransaction();
        try {
            $student = Student::create($request->all());
            DB::commit();
            return response()->json($student);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteStudent(Request $request, $id)
    {
        $student = Student::find($id);
        $student->delete();
        return response()->json($student);
    }

    // attach instrument to student
    public function attachInstrument(Request $request): JsonResponse
    {
        $student = Student::find($request->student_id);
        $student->instruments()->attach($request->instrument_id);
        return response()->json($student);
    }

}
