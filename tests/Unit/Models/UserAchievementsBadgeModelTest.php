<?php

namespace Tests\Unit\Models;

use App\Models\UserAchievementsBadge;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserAchievementsBadgeModelTest extends TestCase
{
    use RefreshDatabase;

    public function testUserAchievementsBadgeCanBeCreated()
    {
        $badgeData = [
            'user_id' => 1,
            'comment_level' => 2,
            'lesson_level' => 3,
            'total_comments' => 10,
            'total_lessons' => 5,
            'badge_level' => 2,
        ];

        $badge = UserAchievementsBadge::create($badgeData);

        $this->assertNotNull($badge);
        $this->assertEquals(1, $badge->user_id);
        $this->assertEquals(2, $badge->comment_level);
        $this->assertEquals(3, $badge->lesson_level);
        $this->assertEquals(10, $badge->total_comments);
        $this->assertEquals(5, $badge->total_lessons);
        $this->assertEquals(2, $badge->badge_level);
    }

    public function testGetTotalAchievements()
    {
        $badge = new UserAchievementsBadge([
            'comment_level' => 2,
            'lesson_level' => 3,
        ]);

        $totalAchievements = $badge->getTotalAchievements();

        $this->assertEquals(5, $totalAchievements);
    }
}
