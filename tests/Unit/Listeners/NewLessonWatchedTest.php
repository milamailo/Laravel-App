<?php

namespace Tests\Unit\Listeners;

use Tests\TestCase;
use App\Events\LessonWatched;
use App\Models\User;
use App\Models\UserAchievementsBadge;
use App\Models\Achievements;
use App\Models\Lesson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

class NewLessonWatchedTest extends TestCase
{
    use RefreshDatabase;

    public function testHandleLessonAchievement()
    {
        $user = User::factory()->create();
        $lesson = Lesson::factory()->create();

        // Create a new lesson watched event
        $lessonWatchedEvent = new LessonWatched($lesson, $user);
        Event::dispatch($lessonWatchedEvent);

        // Retrieve the user's achievements badge
        $userAchievementsBadge = UserAchievementsBadge::where('user_id', $user->id)->first();

        // Assert that the user's achievements badge exists
        $this->assertNotNull($userAchievementsBadge);
        // Assert that the user's lesson level has been updated to 1
        $this->assertEquals(1, $userAchievementsBadge->lesson_level);
        // Assert that the total_lessons has been incremented by 1
        $this->assertEquals(1, $userAchievementsBadge->total_lessons);
    }

    public function testHandleLessonAchievementWithExistingLessonLevel()
    {
        $user = User::factory()->create();
        $lesson = Lesson::factory()->create();
        UserAchievementsBadge::create([
            'user_id' => $user->id,
            'comment_level' => 0,
            'lesson_level' => 3,
            'total_comments' => 0,
            'total_lessons' => 5,
            'badge_level' => 1,
        ]);

        // Create a new lesson watched event
        $lessonWatchedEvent = new LessonWatched($lesson, $user);
        Event::dispatch($lessonWatchedEvent);

        // Retrieve the user's achievements badge
        $userAchievementsBadge = UserAchievementsBadge::where('user_id', $user->id)->first();
        $this->assertEquals(3, $userAchievementsBadge->lesson_level);
        $this->assertEquals(6, $userAchievementsBadge->total_lessons);
    }
}
