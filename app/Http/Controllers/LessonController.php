<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


/**
 * Class LessonController
 * Create here the methods for managing  register lesson(s)
 * not the actual lesson itself tha the student will take
 * but this like the original agreement between the institution and the student
 * e.g. "the student will take 2 lessons per week for 3 months"
 * */
class LessonController extends Controller
{// use try catch and DB::beginTransaction() and DB::commit() and DB::rollBack()

    public function getLessons(Request $request)
    {
        return response()->json(Lesson::all());
    }

    public function getLesson(Request $request)
    {
        return response()->json(Lesson::find($request->id));
    }

    public function updateLesson(Request $request)
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

    public function createLesson(Request $request)
    {
        // create a lesson
        DB::beginTransaction();
        try {
            $lesson = Lesson::create($request->all());
            DB::commit();
            return response()->json($lesson);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteLesson(Request $request, $id)
    {
        // delete a lesson
    }


}
