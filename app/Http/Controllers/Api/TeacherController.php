<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TeacherResource;
use App\Models\teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get all teachers with paginate 20 pre page
        $teachers = Teacher::paginate(20);

        // Retrieve all teachers with their associated user details
        $teachers2 = Teacher::with('user')->get();

        return response()->json([
            'status' => 200,
            'data' => TeacherResource::collection($teachers),
            'data2' => new TeacherResource($teachers2)
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate [user_id, qualification, bio]
        $request->validate([
            'user_id' => 'required|exists:users,id|unique:teachers,user_id',
            'qualification' => 'nullable|string',
            'bio' => 'nullable|string'
        ]);

        // Create Teacher - Store
        $teacher = Teacher::create([
            'user_id' => $request->user_id,
            'qualification' => $request->qualification,
            'bio' => $request->bio
        ]);

        // Redirect
        return response()->json([
            'status' => 201,
            'message' => 'Teacher created successfully',
            'data' => $teacher
        ], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(teacher $teacher)
    {
        return response()->json([
            'status' => 200,
            'data' => new TeacherResource($teacher)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Teacher $teacher)
    {
        // Validate
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id|unique:teachers,user_id,' . $teacher->id,
            'qualification' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string']
        ]);

        // Update
        $teacher->update($validatedData);

        // Ensure we return the updated data
        $teacher->refresh();

        // Response
        return response()->json([
            'status' => 200,
            'message' => 'Teacher updated successfully',
            'data' => new TeacherResource($teacher)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(teacher $teacher)
    {
        // Delete the teacher
        $teacher->delete();

        // Redirect
        return response()->json([
            'status' => 200,
            'message' => 'Teacher deleted successfully'
        ], 200);

    }
}
