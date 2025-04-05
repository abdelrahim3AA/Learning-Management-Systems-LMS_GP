<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AssignmentResource;
use App\Models\Assignment;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    /**
     * Display a listing of assignments.
     */
    public function index()
    {
        $assignments = Assignment::with('lesson')->latest()->paginate(10);

        return response()->json([
            'status' => 200,
            'data' => AssignmentResource::collection($assignments),
            'pagination' => [
                'current_page' => $assignments->currentPage(),
                'last_page' => $assignments->lastPage(),
                'total' => $assignments->total(),
            ]
        ]);
    }

    /**
     * Store a newly created assignment.
     */
    public function store(Request $request)
    {
        $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'due_date' => 'required|date|after:today',
        ]);

        $assignment = Assignment::create($request->all());

        return response()->json([
            'status' => 201,
            'data' => new AssignmentResource($assignment),
        ], 201);
    }

    /**
     * Display the specified assignment.
     */
    public function show(Assignment $assignment)
    {
        return response()->json([
            'status' => 200,
            'data' => new AssignmentResource($assignment),
        ], 200);
    }

    /**
     * Update the specified assignment.
     */
    public function update(Request $request, Assignment $assignment)
    {
        $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'due_date' => 'sometimes|date|after:today',
        ]);

        $assignment->update($request->only('title', 'description', 'due_date'));

        return response()->json([
            'status' => 200,
            'message' => 'Assignment updated successfully.',
            'data' => new AssignmentResource($assignment),
        ], 200);
    }

    /**
     * Delete the specified assignment.
     */
    public function destroy(Assignment $assignment)
    {
        $assignment->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Assignment deleted successfully.',
        ], 200);
    }
}
