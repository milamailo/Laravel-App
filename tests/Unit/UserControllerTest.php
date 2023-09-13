<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Comment;
use App\Models\Lesson;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Sanctum;
use Mockery;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateUser()
    {
        $userData = [
            'name' => 'Milad',
            'email' => 'milad@example.com',
            'password' => 'pass1234',
        ];

        // Send a POST request to the createUser endpoint
        // Assert that the response status code is 200 (Created)
        // Assert that the user was created in the database
        $response = $this->json('POST', '/api/register', $userData);
        $response->assertStatus(200);
        $this->assertDatabaseHas('users', ['email' => 'milad@example.com']);
    }

    public function testUserLogin()
    {
        $loginData = [
            'email' => 'milad@example.com',
            'password' => 'pass1234',
        ];

        // Create a user in the database
        $user = User::factory()->create([
            'email' => 'milad@example.com',
            'password' => bcrypt('pass1234'),
        ]);

        // Send a POST request to the userLogin endpoint
        // Assert that the response status code is 200 (OK)
        // Assert that the response contains a token
        $response = $this->json('POST', '/api/login', $loginData);
        $response->assertStatus(200);
        $response->assertJsonStructure(['token']);
    }

    public function testGetUserComments()
    {
        // Create a user and authenticate using Sanctum
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Add user comments to the database
        // Send a GET request to the getUserComments endpoint
        // Assert that the response status code is 200 (OK)
        // Assert the response contains expected data
        $comment = Comment::create(['user_id' => $user->id, 'body' => 'Test Comment']);
        $response = $this->json('GET', '/api/user/comments');
        $response->assertStatus(200);
        $response->assertJson([
            'user' => [
                'comments' => [
                    [
                        'user_id' => $user->id,
                        'body' => 'Test Comment',
                    ],
                ],
            ],
        ]);
    }

    public function testGetUserwatchedLessons()
    {
        // Create a user and authenticate using Sanctum
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Add user watched lessons to the database
        // Send a GET request to the getUserwatchedLessons endpoint
        // Assert that the response status code is 200 (OK)
        // Assert the response contains expected data
        $this->seed();
        DB::table('lesson_user')->insert([
            'user_id' => $user->id,
            'lesson_id' => 1,
        ]);
        $response = $this->json('GET', '/api/user/lessons');
        $response->assertStatus(200);
        $response->assertJson([
            'user' => [
                'lessons' => [],
            ],
        ]);
    }
}
