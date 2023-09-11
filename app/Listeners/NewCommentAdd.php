<?php

namespace App\Listeners;

use App\Events\CommentWritten;
use App\Models\User;
use App\Models\UserAchievementsBadge;
use App\Models\Achievements;
use Illuminate\Support\Facades\Log;


class NewCommentAdd
{
    /**
     * Handle the event.
     */
    public function handle(object $event)
    {
        try {
            $payload = [];
            $comment = $event->comment;
            $userId = $comment->user_id;
            $user = User::where('id', $userId)->first();
            Log::info('NewCommentAdd listener executed for user: ' . $user->id);

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

            // Set type to payload to fired AchievementUnlocked event based on its total_comments
            $achievements = Achievements::where('type', 'comment')->get();
            foreach ($achievements as $achievement) {
                if (($achievement->level == $userAchievementsBadge->comment_level)) {
                    if ($achievement->max_points >= $userAchievementsBadge->total_comments) {
                        $payload = [
                            'user' => $user,
                        ];
                    } else {
                        $userAchievementsBadge->comment_level += 1;
                        $payload = [
                            'type' => 'comment',
                            'user' => $user,
                        ];
                    }
                    break;
                }
            }

            $userAchievementsBadge->save();

            return $payload;
        } catch (\Throwable $th) {
            // Handle exceptions and return an error response
            return $th->getMessage();
        }
    }
}
