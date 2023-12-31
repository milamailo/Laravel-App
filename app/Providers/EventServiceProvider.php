<?php

namespace App\Providers;

use App\Events\AchievementUnlockEvent;
use App\Events\BadgeUnlockedEvent;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\CommentWritten;
use App\Events\LessonWatched;
use App\Listeners\LevelUpAchievements;
use App\Listeners\NewBadgeAchieved;
use App\Listeners\NewCommentAdded;
use App\Listeners\NewLessonWatched;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        CommentWritten::class => [
            NewCommentAdded::class,
        ],
        AchievementUnlockEvent::class => [
            LevelUpAchievements::class,
        ],
        LessonWatched::class => [
            NewLessonWatched::class,
        ],
        BadgeUnlockedEvent::class => [
            NewBadgeAchieved::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
