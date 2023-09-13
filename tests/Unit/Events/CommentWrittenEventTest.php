<?php

namespace Tests\Unit\Events;

use App\Events\CommentWritten;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CommentWrittenEventTest extends TestCase
{
    use RefreshDatabase;

    public function testCommentWrittenEventIsDispatched()
    {
        Event::fake();

        $comment = Comment::factory()->create();

        event(new CommentWritten($comment));

        Event::assertDispatched(CommentWritten::class, function ($event) use ($comment) {
            return $event->comment->id === $comment->id;
        });
    }
}
