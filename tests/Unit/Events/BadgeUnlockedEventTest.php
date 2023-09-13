<?php

namespace Tests\Unit\Events;

use App\Events\BadgeUnlockedEvent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BadgeUnlockedEventTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the BadgeUnlockedEvent can be created.
     */
    public function testBadgeUnlockedEventCanBeCreated()
    {
        // Create a user
        $user = User::factory()->create();

        // Create a BadgeUnlockedEvent instance
        $event = new BadgeUnlockedEvent($user);

        // Assert that the event was created successfully
        $this->assertInstanceOf(BadgeUnlockedEvent::class, $event);
        $this->assertSame($user, $event->user);
    }
}
