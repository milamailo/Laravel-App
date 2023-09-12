<?php

namespace App\Listeners;

use App\Models\Achievements;
use App\Models\UserAchievementsBadge;
use Illuminate\Support\Facades\Log;

class NewBadgeAchieved
{


    /**
     * Handle the event.
     */
    public function handle(object $event)
    {
        try {
            $user = $event->user;
            $badgeName = '';

            // Check if UserAchievementsBadge record exists for the user
            $userAchievementsBadge = UserAchievementsBadge::where('user_id', $user->id)->first();
            if ($userAchievementsBadge) {
                $badges = Achievements::where('type', 'badge')
                    ->where('level', $userAchievementsBadge->badge_level)
                    ->first();
                // Log::info($userAchievementsBadge->getTotalAchievements());
                if ($userAchievementsBadge->getTotalAchievements() > $badges->max_points) {
                    $userAchievementsBadge->badge_level += 1;
                    $userAchievementsBadge->save();
                    $badgeName = Achievements::where('type', 'badge')
                        ->where('level', $userAchievementsBadge->badge_level)
                        ->pluck('name')
                        ->first();
                    // Log::info($badgeName);
                }
            }

            return [
                'badge_name' => $badgeName,
            ];
        } catch (\Throwable $th) {
            // Handle exceptions and return an error response
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
