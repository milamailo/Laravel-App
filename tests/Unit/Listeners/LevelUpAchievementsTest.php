<?php

namespace Tests\Unit\Listeners;

use Tests\TestCase;
use App\Events\AchievementUnlockEvent;
use App\Models\User;
use App\Models\UserAchievementsBadge;
use App\Models\Achievements;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

class LevelUpAchievementsTest extends TestCase
{
    use RefreshDatabase;

    public function testHandleCommentAchievement()
    {
        $user = User::factory()->create();
        UserAchievementsBadge::create([
            'user_id' => $user->id,
            'comment_level' => 1,
            'lesson_level' => 0,
            'total_comments' => 1,
            'total_lessons' => 0,
            'badge_level' => 1,
        ]);

        // Create an achievement unlock event for comment
        $event = new AchievementUnlockEvent($user, 'comment');
        Event::dispatch($event);

        // Refresh the user's achievements badge
        $userAchievementsBadge = UserAchievementsBadge::where('user_id', $user->id)->first();
        $this->assertEquals(1, $userAchievementsBadge->comment_level);
    }

    public function testHandleLessonAchievement()
    {
        $user = User::factory()->create();
        UserAchievementsBadge::create([
            'user_id' => $user->id,
            'comment_level' => 0,
            'lesson_level' => 1,
            'total_comments' => 0,
            'total_lessons' => 1,
            'badge_level' => 1,
        ]);

        // Create an achievement unlock event for lesson
        $event = new AchievementUnlockEvent($user, 'lesson');
        Event::dispatch($event);

        // Refresh the user's achievements badge
        $userAchievementsBadge = UserAchievementsBadge::where('user_id', $user->id)->first();
        $this->assertEquals(1, $userAchievementsBadge->lesson_level);
    }
}
