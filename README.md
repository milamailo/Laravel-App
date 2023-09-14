# Back-end Developer Test

## Introduction

The Course Portal Achievements System is a feature within our course portal platform that enhances the user experience by allowing users to unlock achievements and earn badges based on their interactions with the platform. This README provides an overview of the functionality and concepts behind this system.

## Installation

1. Clone the Repo
    - HTTPS:
    ```md
    git clone https://github.com/miladesmailpour/developer-test.git
    ```
    - SSH:
    ```md
    git clone git@github.com:miladesmailpour/developer-test.git
    ```
2. Install the Composer
    ```md
    composer install
    ```
3. Update the database details in .env
    ```md
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=laravel
    DB_USERNAME=root
    DB_PASSWORD= password \\Repalce With your password
    ```
4. Migrate the database
    ```md
    php artisan migrate
    ```
5. Seed the databsase
    ```md
    php artisan db:seed
    ```
6. Run the server
    ```md
    php artisan serve
    ```

## Functionality

### Event System

The Achievements System is event-driven. It listens for specific user events and triggers the unlocking of achievements and badges accordingly. The two main events are:

1. `LessonWatched`: Fired when a user watches a lesson.
2. `CommentWritten`: Fired when a user writes a comment.
3. `AchievementUnlockEvent`: Fired when a Achievement Unlock "comment or lesson".
4. `BadgeUnlockedEvent`: Fired when a Badge Unlocked.

### Listeners

1. `LevelUpAchievements`: Listen to New Achievement Unlock "comment or lesson".
2. `NewBadgeAchieved`: Listen to New Badge Unlock.
3. `NewCommentAdded`: Listen to New Comment adedd.
4. `NewLessonWatched`: Listen to New Lesson Watched.

### Models

1. `Achievements`
2. `Comment`
3. `Lesson`
4. `User`
5. `UserAchievementsBadge`

### Controllers

1. `CommentController`
2. `LessonController`
3. `UserController`

### Routes

#### No Require Authentication

1. `/register`
2. `/login`

#### Authentication Require

3. `/comment`
4. `/lesson`
5. `/user/comments`
6. `/user/lessons`

### Achievements Endpoint

The system provides an API endpoint to retrieve information about a user's achievements. The endpoint, `users/{user}/achievements`, returns the following:

-   `unlocked_achievements`: An array of the user's unlocked achievements by name.
-   `next_available_achievements`: An array of the next achievements the user can unlock by name.
-   `current_badge`: The name of the user's current badge.
-   `next_badge`: The name of the next badge the user can earn.
-   `remaining_to_unlock_next_badge`: The number of additional achievements the user must unlock to earn the next badge.

## Concepts

### Event-Driven Architecture

The Achievements System follows an event-driven architecture. It listens for user events and reacts to them by unlocking achievements and badges. This decoupled approach allows for flexibility and scalability in handling user interactions.

### User-Centric Design

The system focuses on enhancing the user experience by providing tangible rewards for engaging with the platform. Achievements and badges serve as incentives for users to watch more lessons and write more comments, increasing their interaction with the portal.

## Conclusion

The Course Portal Achievements System is a user-centric feature that by unlocking achievements and earning badges, users are encouraged to participate more actively in the learning process. The event-driven architecture ensures seamless integration with existing platform functionalities.

[Milad Esmaeelpour](https://github.com/miladesmailpour)
