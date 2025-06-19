<?php

namespace App\Http\Controllers;

use App\Models\LessonInstance;
use App\Models\Student;
use App\Models\Teacher;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
    public function  getLessonInstances(Request $request): JsonResponse
    {
        return response()->json(LessonInstance::where("lesson_id", $request->lesson_id)->get());
    }

    /**
     * get a specific lesson instance
     */
    public function getLessonInstance(Request $request): JsonResponse
    {
        return response()->json(LessonInstance::find($request->id));
    }

    public function updateLessonInstance(Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'id' => 'required|exists:lesson_instances,id',
                'status' => 'nullable|in:scheduled,in_progress,completed,cancelled',
            ]);
            $lessonInstance = LessonInstance::find($request->id);
            $lessonInstance->update($request->all());
            DB::commit();
            return response()->json([
                'message' => 'Lesson instance updated successfully to ' . $request->status,
                'data' => $lessonInstance,
                '_t' => "success",
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Lesson instance update failed!',
                'error' => $e->getMessage(),
                '_t' => "error",
            ], 500);
        }
    }

    public function createLessonInstances(Request $request): JsonResponse
    {
        $startDate = Carbon::parse($request->start_date);
        $newDate = $this->addCustomWeeks($startDate, $request->frequency);

        $startOfWeek = $startDate->copy()->startOfWeek(Carbon::SUNDAY);
        $daysPassed = $startDate->diffInDays($startOfWeek);
        if(true){
            // condition if we should count for the days missed in the first wee
            // if the start date is not the start of the week
            $newDate->addDays($daysPassed);
        }

        $endDate = Carbon::parse($newDate);
        $period = CarbonPeriod::create($startDate, $endDate);
        $instances = [];
        foreach ($period as $date) {
            $a = $date->dayOfWeek;
            if ( array_key_exists($a, $request->planning) ) {
                $days_plannings = $request->planning[$a];
                foreach ($days_plannings as $day_planning) {
                    $dateTime = $date->copy()->setTimeFromTimeString($day_planning['time']);

                    $instances[] =  [
                        'lesson_id' => $request->lesson_id,
                        'start' => $dateTime->format('Y-m-d H:i:s'),
                        'room_id' => $request->room_id,
                        'duration' => $request->instrument_plan['duration'],
                    ];
                }
            }
        }

        DB::beginTransaction();
        try {
            LessonInstance::insert($instances);
            DB::commit();
            return response()->json($instances);
        } catch (\Exception $e) {
            DB::rollBack();
            // throw an exception error
            throw $e;
        }
    }

    private function addCustomWeeks(Carbon $date, int $weeks): Carbon
    {
        // Calculate the days from the next day after the start date to Saturday
        $startDayOfWeek = $date->dayOfWeek; // 3 (Wednesday)
        $daysToEndOfFirstWeek = 6 - $startDayOfWeek; // Days to get to Saturday (6 - 3 = 3)

        // Total days in the first custom week (exclude the start date itself)
        $firstWeekDays = $daysToEndOfFirstWeek; // 3 days (25, 26, 27)

        // Total days for the remaining full weeks (excluding the first partial week)
        $fullWeeksDays = ($weeks - 1) * 7; // Full weeks (3 full weeks in this case)

        // Calculate the total days to add
        $totalDaysToAdd = $firstWeekDays + $fullWeeksDays; // 3 + 21 = 24 days

        // Add the total days to the start date
        return $date->copy()->addDays($totalDaysToAdd);
    }

    public function getTeacherLessonInstances(Request $request): JsonResponse
    {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
        ]);
        $teacher = Teacher::find($request->teacher_id);
        return response()->json([
            'instances' => $teacher->lessonInstances()->with('lesson')->get(),
        ]);
    }

    public function getStudentLessonInstances(Request $request): JsonResponse
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
        ]);
        $student = Student::find($request->student_id);
        return response()->json([
            'instances' => $student->lessonInstances()->with('lesson')->get(),
        ]);
    }

    /**
     * Add more lesson instances to an existing lesson (for lesson renewal)
     */
    public function addLessonInstances(Request $request): JsonResponse
    {
        $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'teacher_id' => 'nullable|exists:teachers,id',
            'room_id' => 'nullable|exists:rooms,id',
            'planning' => 'required|array',
            'planning.*' => 'required|array',
            'planning.*.*' => 'required|array',
            'planning.*.*.day' => 'required|integer|min:0|max:6',
            'frequency' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'instrument_plan' => 'required|array',
            'instrument_plan.duration' => 'required|integer',
        ]);

        DB::beginTransaction();
        try {
            $lesson = \App\Models\Lesson::with('instances')->find($request->lesson_id);
            
            // Update lesson fields if provided (but NOT teacher_id - that's only for new instances)
            $updateData = [];
            if ($request->has('room_id') && $request->room_id) {
                $updateData['room_id'] = $request->room_id;
            }
            if ($request->has('frequency')) {
                $updateData['frequency'] = $request->frequency;
            }
            
            // Don't update lesson planning yet - we'll calculate it after adding new instances
            
            if (!empty($updateData)) {
                $lesson->update($updateData);
            }

            // Create new lesson instances
            $startDate = Carbon::parse($request->start_date);
            $newDate = $this->addCustomWeeks($startDate, $request->frequency);

            $startOfWeek = $startDate->copy()->startOfWeek(Carbon::SUNDAY);
            $daysPassed = $startDate->diffInDays($startOfWeek);
            if(true){
                $newDate->addDays($daysPassed);
            }

            $endDate = Carbon::parse($newDate);
            $period = CarbonPeriod::create($startDate, $endDate);
            $instances = [];
            
            foreach ($period as $date) {
                $a = $date->dayOfWeek;
                if ( array_key_exists($a, $request->planning) ) {
                    $days_plannings = $request->planning[$a];
                    foreach ($days_plannings as $day_planning) {
                        $dateTime = $date->copy()->setTimeFromTimeString($day_planning['time']);

                        $instances[] =  [
                            'lesson_id' => $request->lesson_id,
                            'start' => $dateTime->format('Y-m-d H:i:s'),
                            'room_id' => $request->room_id ?? $lesson->room_id,
                            'teacher_id' => $request->teacher_id ?? $lesson->teacher_id,
                            'duration' => $request->instrument_plan['duration'],
                            'status' => 'scheduled',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
            }

            if (!empty($instances)) {
                LessonInstance::insert($instances);
            }
            
            // Now calculate the combined planning from all lesson instances (old + new)
            $allInstances = $lesson->instances()->get(); // Get all instances including the new ones
            $combinedPlanning = $this->calculateCombinedPlanning($allInstances);
            
            // Update the lesson with the combined planning
            $lesson->update(['planning' => $combinedPlanning]);
            
            DB::commit();
            
            return response()->json([
                "result" => $lesson->fresh(['instances', 'teacher', 'student', 'instrument', 'room']),
                "instances_added" => count($instances),
                "message" => "Lesson instances added successfully",
                "_t" => "success",
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Failed to add lesson instances',
                '_t' => 'error',
            ], 500);
        }
    }

    /**
     * Calculate combined planning from all lesson instances
     */
    private function calculateCombinedPlanning($instances)
    {
        $planning = [];
        
        foreach ($instances as $instance) {
            $startDate = Carbon::parse($instance->start);
            $dayOfWeek = $startDate->dayOfWeek;
            $timeString = $startDate->format('H:i:s');
            
            if (!isset($planning[$dayOfWeek])) {
                $planning[$dayOfWeek] = [];
            }
            
            // Check if this time slot already exists to avoid duplicates
            $existingSlot = collect($planning[$dayOfWeek])->first(function ($slot) use ($timeString) {
                return $slot['time'] === $timeString;
            });
            
            if (!$existingSlot) {
                $planning[$dayOfWeek][] = [
                    'time' => $timeString,
                    'day' => $dayOfWeek
                ];
            }
        }
        
        return $planning;
    }
}
