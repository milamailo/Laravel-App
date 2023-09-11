<?php

namespace App\Listeners;

use App\Events\AchievementUnlockEvent;
use App\Models\Achievements;
use App\Models\UserAchievementsBadge;
use Illuminate\Support\Facades\Log;

class LevelUpCommentAchievement
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event)
    {
        $user = $event->user;
        $achievementType = $event->type;

        // Fetch the user's achievements badge from the database using the user's ID
        $userAchievementsBadge = UserAchievementsBadge::where('user_id', $user->id)->first();
        if ($userAchievementsBadge->total_comments != 1) {
            $userAchievementsBadge->comment_level += 1;
            $userAchievementsBadge->save();
        }

        // Query the Achievements table to find the achievement name based on type and comment_level
        $achievementName = Achievements::where('type', $achievementType)
            ->where('level', $userAchievementsBadge->comment_level)
            ->pluck('name')
            ->first();

        return [
            'achievement_name' => $achievementName,
            'user' => $user
        ];
    }
}
