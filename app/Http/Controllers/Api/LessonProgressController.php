<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LessonProgressResource;
use App\Models\LessonProgress;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LessonProgressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $progress = LessonProgress::paginate(10);

        return response()->json([
            'status' => 200,
            'data' => LessonProgressResource::collection($progress),
            'pagination' => [
                'current_page' => $progress->currentPage(),
                'last_page' => $progress->lastPage(),
                'total' => $progress->total(),
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'lesson_id' => ['required', 'exists:lessons,id'],
            'progress_percentage' => ['numeric', 'min:0', 'max:100'],
            'status' => ['required', Rule::in(['not_started', 'in_progress', 'completed'])],
            'last_accessed' => ['nullable', 'date'],
            'completed_at' => ['nullable', 'date'],
        ]);

        // Store
        $progress = LessonProgress::create($request->all());

        // Response
        return response()->json([
            'status' => 201,
            'data' => new LessonProgressResource($progress)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(LessonProgress $lessonProgress)
    {
        return response()->json([
            'status' => 200,
            'data' => new LessonProgressResource($lessonProgress),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LessonProgress $lessonProgress)
    {
        // Validation
        $validatedData = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'lesson_id' => ['required', 'exists:lessons,id'],
            'progress_percentage' => ['numeric', 'min:0', 'max:100'],
            'status' => ['required', Rule::in(['not_started', 'in_progress', 'completed'])],
            'last_accessed' => ['nullable', 'date'],
            'completed_at' => ['nullable', 'date'],
        ]);

        // Update
        $lessonProgress->update($validatedData);

        return response()->json([
            'status' => 200,
            'data' => new LessonProgressResource($lessonProgress)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LessonProgress $lessonProgress)
    {
        $lessonProgress->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Lesson progress deleted successfully'
        ], 200);
    }
}
