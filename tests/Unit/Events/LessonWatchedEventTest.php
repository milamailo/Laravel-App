<?php

namespace Tests\Unit\Events;

use App\Events\LessonWatched;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class LessonWatchedEventTest extends TestCase
{
    use RefreshDatabase;

    public function testLessonWatchedEventIsDispatched()
    {
        Event::fake();

        $user = User::factory()->create();
        $lesson = Lesson::factory()->create();

        event(new LessonWatched($lesson, $user));

        Event::assertDispatched(LessonWatched::class, function ($event) use ($lesson, $user) {
            return $event->lesson->id === $lesson->id && $event->user->id === $user->id;
        });
    }
}
