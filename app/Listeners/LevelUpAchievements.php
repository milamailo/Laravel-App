<?php

namespace App\Listeners;

use App\Events\AchievementUnlockEvent;
use App\Models\Achievements;
use App\Models\UserAchievementsBadge;
use Illuminate\Support\Facades\Log;

class LevelUpAchievements
{
    /**
     * Handle the event.
     */
    public function handle(object $event)
    {
        $user = $event->user;
        $achievementType = $event->type;
        $total = '';
        $level = '';

        // Determined the type of achievement
        if ($achievementType == 'comment') {
            $total = 'total_comments';
            $level = 'comment_level';
        } else if ($achievementType == 'lesson') {
            $total = 'total_lessons';
            $level = 'lesson_level';
        }

        // Fetch the user's achievements badge from the database using the user's ID
        $userAchievementsBadge = UserAchievementsBadge::where('user_id', $user->id)->first();
        if ($userAchievementsBadge->$total != 1) {
            $userAchievementsBadge->$level += 1;
            $userAchievementsBadge->save();
        }

        // Query the Achievements table to find the achievement name based on type and level
        $achievementName = Achievements::where('type', $achievementType)
            ->where('level', $userAchievementsBadge->$level)
            ->pluck('name')
            ->first();

        return [
            'achievement_name' => $achievementName,
            'user' => $user
        ];
    }
}
