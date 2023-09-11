<?php

namespace App\Http\Controllers\Api;

use App\Events\AchievementUnlockEvent;
use App\Events\CommentWritten;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return Comment::all();
        } catch (\Throwable $th) {
            // Handle exceptions and return an error response
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $payload = [];
        $response = [
            'status' => true,
            'message' => 'Comment Created Successfully'
        ];
        try {
            // Validate user input data
            $validateUser = Validator::make($request->all(), [
                'body' => 'required',
                'user_id' => 'required'
            ]);

            // Check if validation fails and return errors if so
            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            // Create a new comment record in the database
            $comment = Comment::create($request->all());

            $commentWrittenEevent = event(new CommentWritten($comment));
            $achievementType = $commentWrittenEevent[0];
            // Log::info('$achievementType');
            // Log::info($achievementType);
            if (isset($achievementType['type'])) {
                $user = User::where('id', $comment->user_id)->first();
                $achievementUnlockEvent = event(new AchievementUnlockEvent($user, $achievementType['type']));
                Log::info('CommentController->store->isset:');
                Log::info($achievementType['type']);
                $payload = $achievementUnlockEvent[0];
                $response['payload'] = $payload;
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return 'Display the specified resource.';
    }
}
