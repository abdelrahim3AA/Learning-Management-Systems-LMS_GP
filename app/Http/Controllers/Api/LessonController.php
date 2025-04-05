<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LessonResource;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lessons = Lesson::paginate(10);

        return response()->json([
            'status' => 200,
            'data' => LessonResource::collection($lessons),
            'pagination' => [
                'current_page' => $lessons->currentPage(),
                'last_page' => $lessons->lastPage(),
                'total' => $lessons->total(),
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
            'course_id' => ['required', 'exists:courses,id'],
            'title' => 'required|string|max:255',
            'content' => 'required|string'
        ]);

        // Store
        $lesson = Lesson::create([
            'course_id' => $request->course_id,
            'title' => $request->title,
            'content' => $request->content
        ]);

        // Response
        return response()->json([
            'status' => 201,
            'data' => new LessonResource($lesson)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Lesson $lesson)
    {
        return response()->json([
            'status' => 200,
            'data' => new LessonResource($lesson),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lesson $lesson)
    {
        // Validation
        $validatedData = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string']
        ]);

        // Update lesson record
        $lesson->update($validatedData);

        // Return response
        return response()->json([
            'status' => 200,
            'data' => new LessonResource($lesson)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lesson $lesson)
    {
        $lesson->delete();

        return response()->json([
            'status' => 200,
            'message' => 'The lesson was successfully deleted'
        ], 200);
    }
}
