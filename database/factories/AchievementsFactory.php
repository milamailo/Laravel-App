<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Achievements;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Achievements>
 */
class AchievementsFactory extends Factory
{
    private $currentIndex = 0;
    protected $model = Achievements::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Define the structured achievement data
        $achievements = [
            [
                'type' => 'lesson',
                'name' => 'First Lesson Watched',
                'level' => 1,
                'min_points' => 1,
                'max_points' => 4,
            ],
            [
                'type' => 'lesson',
                'name' => '5 Lessons Watched',
                'level' => 2,
                'min_points' => 5,
                'max_points' => 9,
            ],
            [
                'type' => 'lesson',
                'name' => '10 Lessons Watched',
                'level' => 3,
                'min_points' => 10,
                'max_points' => 24,
            ],
            [
                'type' => 'lesson',
                'name' => '25 Lessons Watched',
                'level' => 4,
                'min_points' => 25,
                'max_points' => 49,
            ],
            [
                'type' => 'lesson',
                'name' => '50 Lessons Watched',
                'level' => 5,
                'min_points' => 50,
                'max_points' => 100,
            ],
            [
                'type' => 'comment',
                'name' => 'First Comment Written',
                'level' => 1,
                'min_points' => 1,
                'max_points' => 2,
            ],
            [
                'type' => 'comment',
                'name' => '3 Comments Written',
                'level' => 2,
                'min_points' => 3,
                'max_points' => 4,
            ],
            [
                'type' => 'comment',
                'name' => '5 Comments Written',
                'level' => 3,
                'min_points' => 5,
                'max_points' => 9,
            ],
            [
                'type' => 'comment',
                'name' => '10 Comment Written',
                'level' => 4,
                'min_points' => 10,
                'max_points' => 19,
            ],
            [
                'type' => 'comment',
                'name' => '20 Comment Written',
                'level' => 5,
                'min_points' => 20,
                'max_points' => 100,
            ],
            [
                'type' => 'badge',
                'name' => 'Beginner',
                'level' => 1,
                'min_points' => 0,
                'max_points' => 3,
            ],
            [
                'type' => 'badge',
                'name' => 'Intermediate',
                'level' => 2,
                'min_points' => 4,
                'max_points' => 7,
            ],
            [
                'type' => 'badge',
                'name' => 'Advanced',
                'level' => 3,
                'min_points' => 8,
                'max_points' => 9,
            ],
            [
                'type' => 'badge',
                'name' => 'Master',
                'level' => 4,
                'min_points' => 10,
                'max_points' => 100,
            ],
        ];


        // Get the current achievement data
        $achievementData = $achievements[$this->currentIndex];
        // Increment the current index or reset it to 0 when it reaches the end
        $this->currentIndex = ($this->currentIndex + 1) % count($achievements);


        return [
            'type' => $achievementData['type'],
            'name' => $achievementData['name'],
            'level' => $achievementData['level'],
            'min_points' => $achievementData['min_points'],
            'max_points' => $achievementData['max_points'],
        ];
    }
}
