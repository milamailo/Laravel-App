<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Lesson;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{
    /**
     * Create a new user
     * @param Request $request
     * @return User 
     */
    public function createUser(Request $request)
    {
        try {
            // Validate user input data
            $validateUser = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required'
            ]);

            // Check if validation fails and return errors if so
            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validateUser->errors()
                ], 422);
            }

            // Create a new user record in the database
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            // Return a success response with user data and an authentication token
            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'user' => $user,
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);
        } catch (\Throwable $th) {
            // Handle exceptions and return an error response
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function userLogin(Request $request)
    {
        try {
            // Validate user login input data
            $validateUser = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            // Check if validation fails and return errors if so
            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            // Attempt to authenticate the user with provided email and password
            if (!Auth::attempt($request->only(['email', 'password']))) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password do not match with our records.',
                ], 401);
            }

            // Retrieve the authenticated user
            $user = User::where('email', $request->email)->first();

            // Return a success response with user data and an authentication token
            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);
        } catch (\Throwable $th) {
            // Handle exceptions and return an error response
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function getUserComments(Request $request)
    {
        try {
            // Get the authenticated user
            $user = $request->user();

            // Retrieve all comments associated with the user
            $comments = Comment::where('user_id', $user->id)->get();
            $user->comments = $comments;
            return response()->json([
                'status' => true,
                'message' => 'User Comments Retrieved Successfully',
                'user' => $user
            ], 200);
        } catch (\Throwable $th) {
            // Handle exceptions and return an error response
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function getUserwatchedLessons(Request $request)
    {
        try {
            // Get the authenticated user
            $user = $request->user();

            // Retrieve all watched lessons for the user
            $watchedLessonIds = DB::table('lesson_user')
                ->where('user_id', $user->id)
                ->where('watched', true)
                ->pluck('lesson_id')
                ->toArray();

            // Retrieve the actual lesson records for the watched lesson IDs
            $watchedLessons = Lesson::whereIn('id', $watchedLessonIds)->get();

            // Add the watched lessons to the user
            $user->lessons = $watchedLessons;

            return response()->json([
                'status' => true,
                'message' => 'Watched Lessons Retrieved Successfully',
                'user' => $user
            ], 200);
        } catch (\Throwable $th) {
            // Handle exceptions and return an error response
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
