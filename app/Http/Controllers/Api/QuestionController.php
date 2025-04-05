<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuestionResource;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Display a listing of questions.
     */
    public function index()
    {
        $questions = Question::with('lesson')->latest()->paginate(10);

        return response()->json([
            'status' => 200,
            'data' => QuestionResource::collection($questions),
            'pagination' => [
                'current_page' => $questions->currentPage(),
                'last_page' => $questions->lastPage(),
                'total' => $questions->total(),
            ]
        ]);
    }

    /**
     * Store a newly created question.
     */
    public function store(Request $request)
    {
        $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'question_text' => 'required|string',
            'question_type' => 'required|in:mcq,checkbox,true_false,short_answer,essay',
        ]);

        $question = Question::create([
            'lesson_id' => $request->lesson_id,
            'question_text' => $request->question_text,
            'question_type' => $request->question_type,
        ]);

        return response()->json([
            'status' => 201,
            'data' => new QuestionResource($question),
        ], 201);
    }

    /**
     * Display the specified question.
     */
    public function show(Question $question)
    {
        return response()->json([
            'status' => 200,
            'data' => new QuestionResource($question),
        ], 200);
    }

    /**
     * Update the specified question.
     */
    public function update(Request $request, Question $question)
    {
        $request->validate([
            'question_text' => 'sometimes|required|string',
            'question_type' => 'sometimes|required|in:mcq,checkbox,true_false,short_answer,essay',
        ]);

        $question->update($request->only('question_text', 'question_type'));

        return response()->json([
            'status' => 200,
            'message' => 'Question updated successfully.',
            'data' => new QuestionResource($question),
        ], 200);
    }

    /**
     * Delete the specified question.
     */
    public function destroy(Question $question)
    {
        $question->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Question deleted successfully.',
        ], 200);
    }
}
