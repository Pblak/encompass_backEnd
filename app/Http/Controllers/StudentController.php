<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function getStudents(Request $request): JsonResponse
    {
        $relations = [
            'instruments',
            'parent',
            'transactions',
        ];

        if (auth()->checkTable('users')) {
            $students = $request->withTrashed === "true" ?
                Student::withTrashed()->with($relations)->get() :
                Student::with($relations)->get();
        } else {
            $students = $request->withTrashed === "true" ?
                $request->user()->students()->withTrashed()->with($relations)->get() :
                $request->user()->students()->with($relations)->get();
        }
        return response()->json($students);
    }

    public function getStudent(Request $request, $id): JsonResponse
    {
        return response()->json(Student::with(['instruments'])->find($id));
    }

    public function updateStudent(Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'id' => 'required|exists:students,id',
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'nullable|email|unique:students,email,' . $request->id,
                'parent_id' => 'required|exists:parents,id',
            ]);

            $updateData = (object)$request->except('avatar');

            if ($request->file('infos.avatar')) {
                $imageFile = $request->file('infos.avatar');
                $path = 'images/students/' . $request->id . '/avatar.' . $imageFile->getClientOriginalExtension();
                Storage::disk('public')->put($path, file_get_contents($imageFile->getRealPath()));
                $updateData->infos['avatar'] = 'storage/' . $path;
            }

            $student = Student::find($updateData->id);
            $student->update((array)$updateData);
            DB::commit();
            return response()->json([
                'result' => $student,
                'message' => 'Student updated successfully',
                '_t' => 'success',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage(),
//                'message' => 'Student not updated',
                '_t' => 'error',
            ]);
        }
    }

    public function createStudent(Request $request): JsonResponse
    {
        if ($request->attributes->get('currentGuard') === 'parent') {
            $request->request->set('parent_id',$request->user()->id);
        }
        DB::beginTransaction();
        try {
            $request->validate([

                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'nullable|email|unique:students,email',
                'password' => 'required',
                'parent_id' => 'required|exists:parents,id',
                'username'=> 'required|unique:students,infos->username'
            ]);
            $request->request->set('email',!$request->email?
                $request->username.'@'.env('APP_DOMAIN'):$request->email);

            $student = Student::create($request->all());
            DB::commit();

            return response()->json([
                "result" => $student,
                "message" => "Student created successfully",
                "_t" => "success",
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteStudent(Request $request): JsonResponse
    {

        DB::beginTransaction();
        try {
            $request->validate([
                'id' => 'required|exists:students,id',
            ]);
            $student = Student::find($request->id);
            if ($student->deleted_at) {
                DB::commit();
                return response()->json([
                    'message' => 'Student already deleted',
                    '_t' => 'warning',
                ]);
            }
            $student->delete();
            DB::commit();
            return response()->json([
                'message' => 'Student deleted successfully',
                '_t' => 'success',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Student not deleted',
                '_t' => 'error',
            ], 500);
        }

    }

    // attach instrument to student
    public function attachInstrument(Request $request): JsonResponse
    {
        $student = Student::find($request->student_id);
        $student->instruments()->attach($request->instrument_id);
        return response()->json($student);
    }

}
