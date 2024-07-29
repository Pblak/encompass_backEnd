<?php

namespace App\Http\Controllers;

use App\Models\LessonInstance;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
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
    public function getLessonInstances(Request $request)
    {
        return response()->json(LessonInstance::where("lesson_id", $request->lesson_id)->get());
    }

    /**
     * get a specific lesson instance
     */
    public function getLessonInstance(Request $request)
    {
        return response()->json(LessonInstance::find($request->id));
    }

    /**
     * @throws \Exception
     */
    public function createLessonInstances(Request $request)
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

    private function addCustomWeeks(Carbon $date, int $weeks)
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


}
