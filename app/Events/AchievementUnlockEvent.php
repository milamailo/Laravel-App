<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class AchievementUnlockEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $type;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, string $type)
    {
        $this->user = $user;
        $this->type = $type;
    }
}
