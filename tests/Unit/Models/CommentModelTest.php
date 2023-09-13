<?php

namespace Tests\Unit\Models;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentModelTest extends TestCase
{
    use RefreshDatabase;

    public function testCommentCanBeCreated()
    {
        $user = User::factory()->create();
        
        $commentData = [
            'body' => 'This is a test comment.',
            'user_id' => $user->id,
        ];

        $comment = Comment::create($commentData);

        $this->assertNotNull($comment);
        $this->assertEquals('This is a test comment.', $comment->body);
        $this->assertEquals($user->id, $comment->user_id);
    }

    public function testUserRelationship()
    {
        $user = User::factory()->create();
        $commentData = [
            'body' => 'This is another test comment.',
            'user_id' => $user->id,
        ];

        $comment = Comment::create($commentData);

        $this->assertInstanceOf(User::class, $comment->user);
        $this->assertEquals($user->id, $comment->user->id);
    }
}
