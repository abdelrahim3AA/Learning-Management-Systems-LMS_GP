<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\StudentAnswerResource;
use App\Models\StudentAnswer;
use Illuminate\Http\Request;

class StudentAnswerController extends Controller
{
    /**
     * Display a listing of student answers.
     */
    public function index()
    {
        $answers = StudentAnswer::with(['student', 'question', 'selectedOption'])->latest()->paginate(10);

        return response()->json([
            'status' => 200,
            'data' => StudentAnswerResource::collection($answers),
            'pagination' => [
                'current_page' => $answers->currentPage(),
                'last_page' => $answers->lastPage(),
                'total' => $answers->total(),
            ]
        ]);
    }

    /**
     * Store a newly created student answer.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'question_id' => 'required|exists:questions,id',
            'selected_option_id' => 'nullable|exists:question_options,id',
            'essay_answer' => 'nullable|string',
            'is_correct' => 'nullable|boolean',
        ]);

        $answer = StudentAnswer::create($request->all());

        return response()->json([
            'status' => 201,
            'data' => new StudentAnswerResource($answer),
        ], 201);
    }

    /**
     * Display the specified student answer.
     */
    public function show(StudentAnswer $answer)
    {
        return response()->json([
            'status' => 200,
            'data' => new StudentAnswerResource($answer),
        ], 200);
    }

    /**
     * Update the specified student answer.
     */
    public function update(Request $request, StudentAnswer $answer)
    {
        $request->validate([
            'selected_option_id' => 'nullable|exists:question_options,id',
            'essay_answer' => 'nullable|string',
            'is_correct' => 'nullable|boolean',
        ]);

        $answer->update($request->only('selected_option_id', 'essay_answer', 'is_correct'));

        return response()->json([
            'status' => 200,
            'message' => 'Answer updated successfully.',
            'data' => new StudentAnswerResource($answer),
        ], 200);
    }

    /**
     * Delete the specified student answer.
     */
    public function destroy(StudentAnswer $answer)
    {
        $answer->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Answer deleted successfully.',
        ], 200);
    }
}
