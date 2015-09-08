<?php

namespace App\Events;

use App\Motion;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MotionCreated extends Event
{
    use SerializesModels;

    public $motion;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Motion $motion)
    {
        $this->motion = $motion;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
