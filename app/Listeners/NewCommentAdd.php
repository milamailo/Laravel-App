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
        $comment = $event->comment;
        $userId = $comment->user_id;

        // Check if UserAchievementsBadge record exists for the user
        $userAchievementsBadge = UserAchievementsBadge::where('user_id', $userId)->first();

        // If the record does not exist, create a new one
        if (!$userAchievementsBadge) {
            $userAchievementsBadge = UserAchievementsBadge::create([
                'user_id' => $userId,
                'comment_level' => 0,
                'Lesson_level' => 0,
                'total_comments' => 0,
                'total_lessons' => 0,
                'badge_level' => 0,
            ]);
        }

        // Update the comment_level and total_comments based on user's comments
        $userAchievementsBadge->comment_level += 1;
        $userAchievementsBadge->total_comments += 1;
        $userAchievementsBadge->save();


        // Return the user including their comments
        $user = User::where('id', $userId);
        $user->comments = $comment;

        return $user;
    }
}
