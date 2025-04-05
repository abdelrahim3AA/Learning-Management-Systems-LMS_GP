<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuestionOptionResource;
use App\Models\QuestionOption;
use Illuminate\Http\Request;

class QuestionOptionController extends Controller
{
    /**
     * Display a listing of question options.
     */
    public function index()
    {
        $options = QuestionOption::with('question')->latest()->paginate(10);

        return response()->json([
            'status' => 200,
            'data' => QuestionOptionResource::collection($options),
            'pagination' => [
                'current_page' => $options->currentPage(),
                'last_page' => $options->lastPage(),
                'total' => $options->total(),
            ]
        ]);
    }

    /**
     * Store a newly created question option.
     */
    public function store(Request $request)
    {
        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'option_text' => 'required|string',
            'is_correct' => 'required|boolean',
        ]);

        $option = QuestionOption::create([
            'question_id' => $request->question_id,
            'option_text' => $request->option_text,
            'is_correct' => $request->is_correct,
        ]);

        return response()->json([
            'status' => 201,
            'data' => new QuestionOptionResource($option),
        ], 201);
    }

    /**
     * Display the specified question option.
     */
    public function show(QuestionOption $option)
    {
        return response()->json([
            'status' => 200,
            'data' => new QuestionOptionResource($option),
        ], 200);
    }

    /**
     * Update the specified question option.
     */
    public function update(Request $request, QuestionOption $option)
    {
        $request->validate([
            'option_text' => 'sometimes|required|string',
            'is_correct' => 'sometimes|required|boolean',
        ]);

        $option->update($request->only('option_text', 'is_correct'));

        return response()->json([
            'status' => 200,
            'message' => 'Option updated successfully.',
            'data' => new QuestionOptionResource($option),
        ], 200);
    }

    /**
     * Delete the specified question option.
     */
    public function destroy(QuestionOption $option)
    {
        $option->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Option deleted successfully.',
        ], 200);
    }
}
