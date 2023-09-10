<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


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


            // Return a success response
            return response()->json([
                'status' => true,
                'message' => 'Watched Lesson Add Successfully'
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
