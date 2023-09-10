<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Achievemenets;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Achievemenets>
 */
class AchievemenetsFactory extends Factory
{
    private $currentIndex = 0;
    protected $model = Achievemenets::class;

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
            ],
            [
                'type' => 'lesson',
                'name' => '5 Lessons Watched',
                'level' => 2,
                'min_points' => 5,
            ],
            [
                'type' => 'lesson',
                'name' => '10 Lessons Watched',
                'level' => 3,
                'min_points' => 10,
            ],
            [
                'type' => 'lesson',
                'name' => '25 Lessons Watched',
                'level' => 4,
                'min_points' => 25,
            ],
            [
                'type' => 'lesson',
                'name' => '50 Lessons Watched',
                'level' => 5,
                'min_points' => 50,
            ],
            [
                'type' => 'comment',
                'name' => 'First Comment Written',
                'level' => 1,
                'min_points' => 1,
            ],
            [
                'type' => 'comment',
                'name' => '3 Comments Written',
                'level' => 2,
                'min_points' => 3,
            ],
            [
                'type' => 'comment',
                'name' => '5 Comments Written',
                'level' => 3,
                'min_points' => 5,
            ],
            [
                'type' => 'comment',
                'name' => '10 Comment Written',
                'level' => 4,
                'min_points' => 10,
            ],
            [
                'type' => 'comment',
                'name' => '20 Comment Written',
                'level' => 5,
                'min_points' => 20,
            ],
            [
                'type' => 'badge',
                'name' => 'Beginner',
                'level' => 1,
                'min_points' => 0,
            ],
            [
                'type' => 'badge',
                'name' => 'Intermediate',
                'level' => 2,
                'min_points' => 4,
            ],
            [
                'type' => 'badge',
                'name' => 'Advanced',
                'level' => 3,
                'min_points' => 8,
            ],
            [
                'type' => 'badge',
                'name' => 'Master',
                'level' => 4,
                'min_points' => 10,
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
        ];
    }
}
