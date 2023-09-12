<?php

namespace App\Http\Controllers\Api;

use App\Events\AchievementUnlockEvent;
use App\Events\BadgeUnlockedEvent;
use App\Events\LessonWatched;
use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return Lesson::all(); //from lessons table
        } catch (\Throwable $th) {
            // Handle exceptions and return an error response
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    /**
     * Store a newly created resource in storage, lesson_user table.
     */
    public function store(Request $request)
    {
        $payload = [];
        $response = [
            'status' => true,
            'message' => 'Watched Lessen Added Successfully'
        ];
        try {
            // Validate user input data
            $validateUser = Validator::make($request->all(), [
                'user_id' => 'required',
                'lesson_id' => 'required'
            ]);

            // Check if validation fails and return errors if so
            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            // Retrieve userId and lessonId from the request
            $userId = $request->input('user_id');
            $lessonId = $request->input('lesson_id');

            // Create a new record in the database
            DB::table('lesson_user')->insert([
                'user_id' => $userId,
                'lesson_id' => $lessonId,
                'watched' => true
            ]);
            // Log::info($userId . ' ' . $lessonId);
            // Event .fired every time a comment added
            $user = User::where('id', $userId)->first();
            $lesson = Lesson::where('id', $lessonId)->first();
            $lessonWatchedEevent = event(new LessonWatched(
                $lesson,
                $user
            ));
            $achievementType = $lessonWatchedEevent[0];

            // Event fired every time a comment or lesson watched achievement achieve
            if (isset($achievementType['type'])) {
                $achievementUnlockEvent = event(new AchievementUnlockEvent($user, $achievementType['type']));
                $payload = $achievementUnlockEvent[0];
                $response['payload'] = $payload;
            }

            // Event fired Badge achievement
            $badgeName = event(new BadgeUnlockedEvent($user));
            if (isset($badgeName[0]['badge_name'])) {
                if ($badgeName[0]['badge_name']) {
                    $response['payload']['badge_name'] = $badgeName[0]['badge_name'];
                }
            }

            if (!count($payload)) {
                unset($response['payload']);
            }
            // Return a success response and payload
            return response()->json($response, 200);
        } catch (\Throwable $th) {
            // Handle exceptions and return an error response
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
