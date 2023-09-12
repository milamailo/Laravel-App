<?php

namespace App\Http\Controllers;

use App\Models\Achievements;
use App\Models\User;
use App\Models\UserAchievementsBadge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AchievementsController extends Controller
{
    public function index(User $user)
    {
        try {
            $userId = $user->id;
            $userAchievementsBadge = UserAchievementsBadge::where('user_id', $userId)->first();
            $badges = Achievements::where('type', 'badge')->get();
            $comments = Achievements::where('type', 'comment')->get();
            $lessons = Achievements::where('type', 'lesson')->get();

            $currentBadge = '';
            $nextBadge = '';
            $remainingBadge = 0;
            $unlockedAchievementsName = [];
            $unlockedComments = [];
            $unlockedlessons = [];
            $nextAchievementName = [];

            for ($i = 0; $i < count($badges); $i++) {
                if ($badges[$i]->level == $userAchievementsBadge->badge_level) {
                    $currentBadge = $badges[$i]->name;
                    $nextBadge = $badges[$i + 1]->name;
                    $remainingBadge = (count($badges) - $i) - 1;
                    break;
                }
            }

            for ($i = 0; $i < count($comments) || $i < count($lessons); $i++) {
                if ($comments[$i]->level == $userAchievementsBadge->comment_level) {
                    $unlockedComments = array_slice($comments->toArray(), $i + 1);
                    array_push($nextAchievementName, $unlockedComments[0]['name']);
                }
                if ($lessons[$i]->level == $userAchievementsBadge->lesson_level) {
                    $unlockedlessons = array_slice($lessons->toArray(), $i + 1);
                    array_push($nextAchievementName, $unlockedlessons[0]['name']);
                }
            }
            $unlockedAchievements = array_merge($unlockedComments, $unlockedlessons);

            for ($i = 0; $i < count($unlockedAchievements); $i++) {
                $unlockedAchievementsName[$i] = $unlockedAchievements[$i]['name'];
            }
            for ($i = 0; $i < count($badges); $i++) {
                if ($badges[$i]->level == $userAchievementsBadge->badge_level) {
                    $currentBadge = $badges[$i]->name;
                    $nextBadge = $badges[$i + 1]->name;
                    $remainingBadge = (count($badges) - $i) - 1;
                    break;
                }
            }

            return response()->json([
                'unlocked_achievements' => $unlockedAchievementsName,
                'next_available_achievements' => $nextAchievementName,
                'current_badge' => $currentBadge,
                'next_badge' => $nextBadge,
                'remaining_to_unlock_next_badge' => $remainingBadge
            ]);
        } catch (\Throwable $th) {
            // Handle exceptions and return an error response
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
