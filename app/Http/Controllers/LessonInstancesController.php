<?php

namespace App\Http\Controllers;

use App\Models\LessonInstances;
use Illuminate\Http\Request;

/**
 * Lesson Instances
 * here is where we will create, update, delete and get lesson instances
 * that the user will actually take and complete or cancel
 * we will create it when the  current date of the week and time matches the Lesson
 * time and day of the week.
 *
 * **e.g. "now we are on Monday 10:00 AM and the Registered Lesson is on Monday 10:00 AM and Tuesday 10:00 AM"
 */
class LessonInstancesController extends Controller
{

    /**
     * get the upcoming lessons for the student
     */
    public function getLessonInstances(Request $request)
    {
        return response()->json(LessonInstances::all());
    }

    /**
     * get a specific lesson instance
     */
    public function getLessonInstance(Request $request)
    {
        return response()->json(LessonInstances::find($request->id));
    }

}
