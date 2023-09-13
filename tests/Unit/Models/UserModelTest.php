<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanBeCreated()
    {
        $plainPassword = 'password123';
        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make($plainPassword),
        ]);

        $user = User::where('email', 'john@example.com')->first();

        // Assert that the user exists
        // Assert that the user's name, email, password matches
        $this->assertNotNull($user);
        $this->assertEquals('John Doe', $user->name);
        $this->assertEquals('john@example.com', $user->email);
        $this->assertTrue(Hash::check($plainPassword, $user->password));
    }
}
