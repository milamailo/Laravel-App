<?php

namespace Tests\Unit\Models;

use App\Models\Lesson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LessonModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test creating a lesson.
     */
    public function testLessonCanBeCreated()
    {
        Lesson::create([
            'title' => 'Introduction to Laravel',
        ]);

        $lesson = Lesson::where('title', 'Introduction to Laravel')->first();

        $this->assertNotNull($lesson);
        $this->assertEquals('Introduction to Laravel', $lesson->title);
    }


    /**
     * Test retrieving all lessons.
     */
    public function testAllLessonsCanBeRetrieved()
    {
        Lesson::create([
            'title' => 'Lesson 1',
        ]);

        Lesson::create([
            'title' => 'Lesson 2',
        ]);

        $lessons = Lesson::all();

        $this->assertCount(2, $lessons);
    }
}
