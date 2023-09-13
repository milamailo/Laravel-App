<?php

namespace Tests\Unit\Models;

use App\Models\Achievements;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AchievementsModelTest extends TestCase
{
    use RefreshDatabase;

    public function testAchievementCanBeCreated()
    {
        $achievementData = [
            'type' => 'comment',
            'name' => 'Commenter',
            'level' => 1,
            'min_points' => 0,
            'max_points' => 10,
        ];

        $achievement = Achievements::create($achievementData);

        $this->assertNotNull($achievement);
        $this->assertEquals('comment', $achievement->type);
        $this->assertEquals('Commenter', $achievement->name);
        $this->assertEquals(1, $achievement->level);
        $this->assertEquals(0, $achievement->min_points);
        $this->assertEquals(10, $achievement->max_points);
    }
}
