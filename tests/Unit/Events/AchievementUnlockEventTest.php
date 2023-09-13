<?php

namespace Tests\Unit\Events;

use App\Events\AchievementUnlockEvent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AchievementUnlockEventTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the AchievementUnlockEvent can be created.
     */
    public function testAchievementUnlockEventCanBeCreated()
    {
        // Create a user
        $user = User::factory()->create();

        // Create an AchievementUnlockEvent instance
        $event = new AchievementUnlockEvent($user, 'comment');

        // Assert that the event was created successfully
        $this->assertInstanceOf(AchievementUnlockEvent::class, $event);
        $this->assertSame($user, $event->user);
        $this->assertSame('comment', $event->type);
    }
}
