<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Comment;
use App\Models\Lesson;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Mockery;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    // createUser tests
    public function testCreateUserEmailValidation()
    {
        $userData = [
            'name' => 'Milad',
            'email' => 'milad@example.com',
            'password' => 'pass1234',
        ];

        // Send a POST request to the createUser endpoint
        // Assert that the response status code is 200 
        // Assert that the user was created in the database
        $response = $this->json('POST', '/api/register', $userData);
        $response->assertStatus(200);
        $this->assertDatabaseHas('users', ['email' => 'milad@example.com']);
    }

    public function testCreateUserNameValidation()
    {
        $userData = [
            'name' => 'Milad',
            'email' => 'milad@example.com',
            'password' => 'pass1234',
        ];

        // Send a POST request to the createUser endpoint
        // Assert that the response status code is 200 
        // Assert that the user was created in the database
        $response = $this->json('POST', '/api/register', $userData);
        $response->assertStatus(200);
        $this->assertDatabaseHas('users', ['name' => 'Milad']);
    }

    public function testCreateUserWithValidDataAndPasswordHashing()
    {
        $userData = [
            'name' => 'Milad',
            'email' => 'milad@example.com',
            'password' => 'pass1234',
        ];

        // Send a POST request to the createUser endpoint
        // Assert that the response status code is 200
        // Assert that the user was created in the database
        $response = $this->json('POST', '/api/register', $userData);
        $response->assertStatus(200);
        $this->assertDatabaseHas('users', ['email' => 'milad@example.com']);
        $user = User::where('email', 'milad@example.com')->first();
        $this->assertTrue(Hash::check('pass1234', $user->password));
    }

    public function testCreateUserWithValidData()
    {
        $userData = [
            'name' => 'Milad',
            'email' => 'milad@example.com',
            'password' => 'pass1234',
        ];

        // Send a POST request to the createUser endpoint
        // Assert that the response status code is 200 
        // Assert that the user was created in the database
        $response = $this->json('POST', '/api/register', $userData);
        $response->assertStatus(200);
        $this->assertDatabaseHas('users', ['email' => 'milad@example.com']);
        $response->assertJson([
            'status' => true,
            'message' => 'User Created Successfully',
        ]);
    }

    public function testCreateUserWithInvalidData()
    {
        $invalidUserData = [];

        // Send a POST request to the createUser endpoint with invalid data
        // Assert that the response status code is 422 validation errors
        // Additional assertions for the response structure
        $response = $this->json('POST', '/api/register', $invalidUserData);
        $response->assertStatus(422);
        $response->assertJson([
            'status' => false,
            'message' => 'Validation error',
            'errors' => [
                'name' => ['The name field is required.'],
                'email' => ['The email field is required.'],
                'password' => ['The password field is required.'],
            ],
        ]);

        // Test creating a user with a duplicate email
        // Send a POST request to the createUser endpoint with duplicate email
        // Assert that the response status code is 422, validation errors
        $duplicateEmailUserData = [
            'name' => 'milad',
            'email' => 'milad@example.com',
            'password' => 'pass5678',
        ];
        User::factory()->create([
            'email' => 'milad@example.com',
        ]);
        $response = $this->json('POST', '/api/register', $duplicateEmailUserData);
        $response->assertStatus(422);
        $response->assertJson([
            'status' => false,
            'message' => 'Validation error',
            'errors' => [
                'email' => ['The email has already been taken.'],
            ],
        ]);
    }

    // userLogin tests
    public function testSuccessfulUserLogin()
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

    public function testInvalidEmailFormat()
    {
        $invalidEmailData = [
            'email' => 'invalid-email',
            'password' => 'pass1234',
        ];

        // Send a POST request to the userLogin endpoint with invalid email format
        // Assert that the response status code is 401, validation errors
        $response = $this->json('POST', '/api/login', $invalidEmailData);
        $response->assertStatus(401);
        $response->assertJson([
            'status' => false,
            'message' => 'Validation error',
            'errors' => [
                'email' => ['The email field must be a valid email address.'],
            ],
        ]);
    }

    public function testIncorrectPassword()
    {
        $loginData = [
            'email' => 'milad@example.com',
            'password' => 'incorrect-password',
        ];
    
        // Create a user in the database
        $user = User::factory()->create([
            'email' => 'milad@example.com',
            'password' => bcrypt('pass1234'),
        ]);
    
        // Send a POST request to the userLogin endpoint with incorrect password
        // Assert that the response status code is 401 (Unauthorized)
        $response = $this->json('POST', '/api/login', $loginData);
        $response->assertStatus(401);
        $response->assertJson([
            'status' => false,
            'message' => 'Email & Password do not match with our records.',
        ]);
    }
    
    // getUserComments tests
    public function testGetUserComments()
    {
        // Create a user and authenticate using Sanctum
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Add user comments to the database
        // Send a GET request to the getUserComments endpoint
        // Assert that the response status code is 200 (OK)
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

    // getUserwatchedLessons tests
    public function testGetUserwatchedLessons()
    {
        // Create a user and authenticate using Sanctum
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Add user watched lessons to the database
        // Send a GET request to the getUserwatchedLessons endpoint
        // Assert that the response status code is 200
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
