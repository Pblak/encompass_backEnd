<?php

namespace App\Events;

use App\Models\LessonInstance;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LessonInstanceStatusUpdatedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public array $lessonInstance;

    /**
     * Create a new event instance.
     */
    public function __construct( $lessonInstance=null)
    {
        $this->lessonInstance = $lessonInstance  ? $lessonInstance->toArray() : null;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn(): Channel
    {
        return new Channel('lesson-instance');

    }
}
