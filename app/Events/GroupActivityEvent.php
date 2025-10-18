<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GroupActivityEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $groupId;
    public $userId;
    public $activityType;
    public $activityData;

    /**
     * Create a new event instance.
     */
    public function __construct($groupId, $userId, $activityType, $activityData = [])
    {
        $this->groupId = $groupId;
        $this->userId = $userId;
        $this->activityType = $activityType; // 'post_created', 'comment_created', 'reaction_added'
        $this->activityData = $activityData;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('group.' . $this->groupId),
        ];
    }
}
