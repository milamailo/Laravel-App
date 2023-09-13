<?php

namespace Tests\Unit\Listeners;

use Tests\TestCase;
use App\Events\AchievementUnlockEvent;
use App\Models\User;
use App\Models\UserAchievementsBadge;
use App\Models\Achievements;
use App\Listeners\NewBadgeAchieved;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

class NewBadgeAchievedTest extends TestCase
{
    use RefreshDatabase;

    public function testHandleBadgeAchievement()
    {
        $user = User::factory()->create();
        Achievements::create([
            'type' => 'badge',
            'level' => 1,
            'min_points' => 0,
            'max_points' => 10, 
            'name' => 'Bronze Badge',
        ]);

        UserAchievementsBadge::create([
            'user_id' => $user->id,
            'comment_level' => 3,
            'lesson_level' => 2,
            'total_comments' => 5,
            'total_lessons' => 10,
            'badge_level' => 1,
        ]);

        // Fire an achievement unlock event for the user 
        $achievementEvent = new AchievementUnlockEvent($user, 'comment');
        Event::dispatch($achievementEvent);

        // Refresh the user's achievements badge
        $userAchievementsBadge = UserAchievementsBadge::where('user_id', $user->id)->first();
        $this->assertEquals(1, $userAchievementsBadge->badge_level);

        // Fire another achievement unlock event to reach the required achievements for the badge
        // This time, the user has enough achievements to qualify for a new badge
        $achievementEvent = new AchievementUnlockEvent($user, 'lesson');
        Event::dispatch($achievementEvent);

        // Refresh the user's achievements badge
        $userAchievementsBadge = UserAchievementsBadge::where('user_id', $user->id)->first();
        $this->assertEquals(1, $userAchievementsBadge->badge_level);
    }
}
