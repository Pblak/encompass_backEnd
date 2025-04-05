<?php

namespace App\Http\Controllers;

use App\Models\Instrument;
use App\Models\Lesson;
use App\Models\Parents;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class LessonController
 * Create here the methods for managing register lesson(s)
 * not the actual lesson itself tha the student will take
 * but this like the original agreement between the institution and the student
 * e.g. "the student will take 2 lessons per week for 3 months"
 * */
class LessonController extends Controller
{// use try catch and DB::beginTransaction() and DB::commit() and DB::rollBack()

    public function getLessons(Request $request): JsonResponse
    {
        $relations = [
            'teacher',
            'student',
            'instrument',
            'room',
            'instances',
            'transactions',
        ];
        if (auth()->checkTable('users')) {
            if ($request->subject && $request->id) {
                $request->validate([
                    'subject' => 'required|string|in:teachers,students,parents',
                    'id' => 'required|exists:' . $request->subject . ',id',
                ]);
                $model = [
                    'teachers' => Teacher::class,
                    'students' => Student::class,
                    'parents' => Parents::class,
                ];
                $user = $model[$request->subject]::find($request->id);
                $lessons = $request->get('withTrashed') ?
                    $user->lessons()->withTrashed()->with($relations)->get() :
                    $user->lessons()->with($relations)->get();
            }else{
                $lessons = $request->get('withTrashed') ?
                    Lesson::withTrashed()->with($relations)->get() :
                    Lesson::with($relations)->get();
            }
        } else {
            $lessons = $request->get('withTrashed') ?
                $request->user()->lessons()->withTrashed()->with($relations)->get() :
                $request->user()->lessons()->with($relations)->get();
        }
        return response()->json($lessons);
    }

    public function getLesson(Request $request): JsonResponse
    {
        $relations = [
            'teacher',
            'student',
            'instrument',
            'room',
            'instances',
            'transactions',
        ];
        if (auth()->checkTable('users')) {
            $lesson = Lesson::with($relations)->find($request->id);
        }else {
            $lesson = $request->user()->studentLessons()->with($relations)->find($request->id);
        }
        return response()->json($lesson);
    }

    public function updateLesson(Request $request): JsonResponse
    {
        // update a lesson
        DB::beginTransaction();
        try {
            $lesson = Lesson::find($request->id);
            $lesson->update($request->all());
            DB::commit();
            return response()->json($lesson);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }

    public function createLesson(Request $request): JsonResponse
    {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'instrument_id' => 'required|exists:instruments,id',
            'student_id' => 'required|exists:students,id',
            'instrument_plan.id' => 'required',
            'planning' => 'required|array',
            'planning.*' => 'required|array',
            'planning.*.*' => 'required|array',
            'planning.*.*.day' => 'required|integer|min:0|max:6',
        ]);
        // [['id' => 1, 'name' => 'drum' , ...], ...]
        $plans = Instrument::where('id', $request->instrument_id)->first()->plans;
        // get from plans the plan that has the id of the request->plan['id']
        $instrument_plan = collect($plans)->where('id', $request->instrument_plan['id'])->first();
        $request->merge(['instrument_plan' => $instrument_plan]);


        DB::beginTransaction();
        try {
            $lesson = Lesson::create([
                'teacher_id' => $request->teacher_id,
                'student_id' => $request->student_id,
                'instrument_id' => $request->instrument_id,
                'room_id' => $request->room_id,
                'frequency' => $request->frequency,
                'instrument_plan' => $instrument_plan,
                'planning' => $request->planning,
            ]);
            $request->merge(['lesson_id' => $lesson->id]);

            try {
                $lessonInstance = new LessonInstancesController();
                $lessonInstance->createLessonInstances($request);
                DB::commit();
                return response()->json([
                    "result" => $lesson,
                    "message" => "Lesson created successfully",
                    "_t" => "success",
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json(['error' => $e->getMessage()], 500);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteLesson(Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'id' =>  'required|exists:lessons,id',
            ]);
            $lesson = Lesson::find($request->id);
            if ($lesson->deleted_at) {
                DB::commit();
                return response()->json([
                    'message' => 'Lesson restored successfully',
                    '_t' => 'success',
                ]);
            }
            $lesson->delete();
            DB::commit();
            return response()->json([
                'message' => 'Lesson deleted successfully',
                '_t' => 'success',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Lesson not deleted',
                '_t' => 'error',
            ],  500);
        }
    }


}
