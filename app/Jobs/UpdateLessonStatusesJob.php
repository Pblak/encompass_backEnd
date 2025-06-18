<?php

namespace App\Jobs;

use App\Events\LessonInstanceStatusUpdatedEvent;
use App\Models\LessonInstance;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UpdateLessonStatusesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        try {
            $now = Carbon::now();

            // Only update 'in_progress' lessons to 'completed' when their time has ended
            $completedInstances = LessonInstance::where('status', 'in_progress')
                ->whereRaw('DATE_ADD(start, INTERVAL duration MINUTE) < ?', [$now])
                ->get();

            $completedCount = 0;
            foreach ($completedInstances as $instance) {
                try {
                    $instance->update(['status' => 'completed']);
                    event(new LessonInstanceStatusUpdatedEvent($instance));
                    $completedCount++;
                } catch (\Exception $e) {
                    Log::error("Failed to update lesson instance {$instance->id}: " . $e->getMessage());
                }
            }

            if ($completedCount > 0) {
                Log::info("UpdateLessonStatusesJob: Updated {$completedCount} lesson instances to completed");
            }
        } catch (\Exception $e) {
            Log::error("UpdateLessonStatusesJob failed: " . $e->getMessage());
            throw $e;
        }
    }
}
