<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Events\CommentWritten;
use App\Models\User;
use App\Models\UserAchievementsBadge;
use App\Models\Achievements;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

class NewCommentAddedTest extends TestCase
{
    use RefreshDatabase;
    /**
     * 
     */
    public function testHandleCommentAchievement()
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create([
            'user_id' => $user->id,
        ]);

        // Create a new comment event
        // Fire the comment event
        $commentEvent = new CommentWritten($comment);
        Event::dispatch($commentEvent);

        // Retrieve the user's achievements badge
        $userAchievementsBadge = UserAchievementsBadge::where('user_id', $user->id)->first();

        // Assert that the user's achievements badge exists
        $this->assertNotNull($userAchievementsBadge);
        // Assert that the user's comment level has been updated to 1
        $this->assertEquals(1, $userAchievementsBadge->comment_level);
        // Assert that the total_comments has been incremented by 1
        $this->assertEquals(1, $userAchievementsBadge->total_comments);
    }

    public function testHandleCommentAchievementWithExistingCommentLevel()
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->create([
            'user_id' => $user->id,
        ]);

        // Set an existing comment level for the user
        UserAchievementsBadge::create([
            'user_id' => $user->id,
            'comment_level' => 2,
            'total_comments' => 5,
        ]);
        $commentEvent = new CommentWritten($comment);

        // Fire the comment event
        Event::dispatch($commentEvent);

        // Retrieve the user's achievements badge
        $userAchievementsBadge = UserAchievementsBadge::where('user_id', $user->id)->first();
        $this->assertEquals(2, $userAchievementsBadge->comment_level);
        $this->assertEquals(6, $userAchievementsBadge->total_comments);
    }
}
