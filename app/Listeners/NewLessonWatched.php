<?php

namespace App\Listeners;

use App\Events\LessonWatched;
use App\Models\Achievements;
use App\Models\UserAchievementsBadge;

class NewLessonWatched
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
        $payload = [];
        try {
            $user = $event->user;
            $lesson = $event->lesson;

            // Check if UserAchievementsBadge record exists for the user
            $userAchievementsBadge = UserAchievementsBadge::where('user_id', $user->id)->first();

            // If the record does not exist, create a new one
            if (!$userAchievementsBadge) {
                $userAchievementsBadge = UserAchievementsBadge::create([
                    'user_id' => $user->id,
                    'comment_level' => 0,
                    'Lesson_level' => 1,
                    'total_comments' => 0,
                    'total_lessons' => 0,
                    'badge_level' => 1,
                ]);
            }

            // Update the total_lessons based on user's comments
            $userAchievementsBadge->total_lessons += 1;
            $userAchievementsBadge->save();

            // Set type to payload to fired AchievementUnlocked event based on its total_lessons
            $achievements = Achievements::where('type', 'lesson')->get();
            foreach ($achievements as $achievement) {
                if (($achievement->level == $userAchievementsBadge->Lesson_level)) {
                    if (($userAchievementsBadge->Lesson_level == 1) && ($userAchievementsBadge->total_lessons == 1)) {
                        $payload = [
                            'type' => 'lesson',
                            'user' => $user,
                        ];
                        break;
                    }
                    if ($achievement->max_points >= $userAchievementsBadge->total_lessons) {
                        $payload = [
                            'user' => $user,
                        ];
                    } else {
                        $payload = [
                            'type' => 'lesson',
                            'user' => $user,
                        ];
                    }
                    break;
                }
            }
            return $payload;
        } catch (\Throwable $th) {
            // Handle exceptions and return an error response
            return $th->getMessage();
        }
    }
}
