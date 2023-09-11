<?php

namespace App\Listeners;

use App\Models\User;
use App\Models\UserAchievementsBadge;
use App\Models\Achievements;

class NewCommentAdd
{
    /**
     * Handle the event.
     */
    public function handle(object $event)
    {
        $payload = [];
        $comment = $event->comment;
        $userId = $comment->user_id;
        $user = User::where('id', $userId)->first();

        // Check if UserAchievementsBadge record exists for the user
        $userAchievementsBadge = UserAchievementsBadge::where('user_id', $userId)->first();

        // If the record does not exist, create a new one
        if (!$userAchievementsBadge) {
            $userAchievementsBadge = UserAchievementsBadge::create([
                'user_id' => $userId,
                'comment_level' => 1,
                'Lesson_level' => 0,
                'total_comments' => 0,
                'total_lessons' => 0,
                'badge_level' => 1,
            ]);
        }

        // Update the comment_level and total_comments based on user's comments
        $userAchievementsBadge->total_comments += 1;
        $userAchievementsBadge->save();

        // Update the comment_level based on its total_comments and compare with Achievements then feed the payload
        $achievements = Achievements::where('type', 'comment')->get();
        foreach ($achievements as $achievement) {
            if (($achievement->level == $userAchievementsBadge->comment_level)) {
                if ($achievement->max_points >= $userAchievementsBadge->total_comments) {
                    $payload = [
                        'user' => $user,
                        'comment' => $comment
                    ];
                } else {
                    $payload = [
                        'event' => 'AchievementUnlocked event is required!'
                    ];
                }
                break;
            }
        }


        return $payload;
    }
}
