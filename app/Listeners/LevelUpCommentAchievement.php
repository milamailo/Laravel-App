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
        Log::info('1 LevelUpCommentAchievement listener executed for user: ' . $achievementType);

        $userAchievementsBadge = UserAchievementsBadge::where('user_id', $user->id)->first();
        Log::info('2 LevelUpCommentAchievement listener executed for user: ' . $userAchievementsBadge);
        $achievementName = Achievements::where('level', $userAchievementsBadge->comment_level)
            ->where('type', $achievementType)
            ->pluck('name')
            ->first();
        Log::info('3 LevelUpCommentAchievement listener executed for achievementName: ' . $achievementName);
        return [
            'achievement_name' => $achievementName,
            'user' => $user
        ];
    }
}
