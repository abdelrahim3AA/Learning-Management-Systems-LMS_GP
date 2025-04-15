<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\StudentSubmissionResource;
use App\Models\Assignment;
use App\Models\Student;
use App\Models\StudentSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class StudentSubmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $submissions = StudentSubmission::with(['student.user', 'assignment'])
            ->latest()
            ->paginate(10);

        return response()->json([
            'status' => 200,
            'data' => StudentSubmissionResource::collection($submissions),
            'pagination' => [
                'current_page' => $submissions->currentPage(),
                'last_page' => $submissions->lastPage(),
                'total' => $submissions->total(),
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
            'student_id' => 'required|exists:students,id',
            'assignment_id' => 'required|exists:assignments,id',
            'content' => 'required_without:file_path|string|nullable',
            'file_path' => 'required_without:content|file|max:10240|nullable', // 10MB max
            'notes' => 'nullable|string',
            'status' => 'required|in:submitted,draft'
        ]);

        // Check if assignment exists and is not past due date
        $assignment = Assignment::findOrFail($request->assignment_id);
        if (now() > $assignment->due_date) {
            return response()->json([
                'status' => 422,
                'message' => 'Assignment submission deadline has passed'
            ], 422);
        }

        // Check if student has already submitted this assignment
        $existingSubmission = StudentSubmission::where('student_id', $request->student_id)
            ->where('assignment_id', $request->assignment_id)
            ->first();

        if ($existingSubmission) {
            return response()->json([
                'status' => 422,
                'message' => 'You have already submitted this assignment',
                'data' => new StudentSubmissionResource($existingSubmission)
            ], 422);
        }

        // Handle file upload if present
        $filePath = null;
        if ($request->hasFile('file_path')) {
            $filePath = $request->file('file_path')->store('submissions', 'public');
        }

        // Create submission
        $submission = StudentSubmission::create([
            'student_id' => $request->student_id,
            'assignment_id' => $request->assignment_id,
            'content' => $request->content,
            'file_path' => $filePath ? Storage::url($filePath) : null,
            'notes' => $request->notes,
            'status' => $request->status,
            'submission_date' => now(),
        ]);

        // Load relationships
        $submission->load(['student.user', 'assignment']);

        return response()->json([
            'status' => 201,
            'message' => 'Assignment submitted successfully',
            'data' => new StudentSubmissionResource($submission)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(StudentSubmission $studentSubmission)
    {
        $studentSubmission->load(['student.user', 'assignment', 'reviews.teacher.user']);

        return response()->json([
            'status' => 200,
            'data' => new StudentSubmissionResource($studentSubmission),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StudentSubmission $studentSubmission)
    {
        // Check if submission can be updated (only if not yet reviewed)
        if ($studentSubmission->reviews->count() > 0) {
            return response()->json([
                'status' => 422,
                'message' => 'Cannot update submission after it has been reviewed'
            ], 422);
        }

        // Check if assignment deadline has passed
        if (now() > $studentSubmission->assignment->due_date) {
            return response()->json([
                'status' => 422,
                'message' => 'Assignment submission deadline has passed'
            ], 422);
        }

        // Validation
        $request->validate([
            'content' => 'required_without:file_path|string|nullable',
            'file_path' => 'sometimes|file|max:10240|nullable', // 10MB max
            'notes' => 'nullable|string',
            'status' => 'sometimes|required|in:submitted,draft'
        ]);

        // Handle file upload if present
        if ($request->hasFile('file_path')) {
            // Delete old file if exists
            if ($studentSubmission->file_path) {
                $oldPath = str_replace('/storage/', '', $studentSubmission->file_path);
                Storage::disk('public')->delete($oldPath);
            }

            $filePath = $request->file('file_path')->store('submissions', 'public');
            $studentSubmission->file_path = Storage::url($filePath);
        }

        // Update other fields
        $studentSubmission->content = $request->input('content', $studentSubmission->content);
        $studentSubmission->notes = $request->input('notes', $studentSubmission->notes);
        $studentSubmission->status = $request->input('status', $studentSubmission->status);
        $studentSubmission->updated_at = now();
        $studentSubmission->save();

        // Load relationships
        $studentSubmission->load(['student.user', 'assignment']);

        return response()->json([
            'status' => 200,
            'message' => 'Submission updated successfully',
            'data' => new StudentSubmissionResource($studentSubmission)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StudentSubmission $studentSubmission)
    {
        // Check if submission can be deleted (only if not yet reviewed)
        if ($studentSubmission->reviews->count() > 0) {
            return response()->json([
                'status' => 422,
                'message' => 'Cannot delete submission after it has been reviewed'
            ], 422);
        }

        // Delete file if exists
        if ($studentSubmission->file_path) {
            $path = str_replace('/storage/', '', $studentSubmission->file_path);
            Storage::disk('public')->delete($path);
        }

        $studentSubmission->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Submission was successfully deleted'
        ], 200);
    }

    /**
     * Get all submissions for a specific assignment
     */
    public function assignmentSubmissions(Assignment $assignment)
    {
        $submissions = StudentSubmission::where('assignment_id', $assignment->id)
            ->with(['student.user', 'reviews'])
            ->paginate(10);

        return response()->json([
            'status' => 200,
            'data' => StudentSubmissionResource::collection($submissions),
            'pagination' => [
                'current_page' => $submissions->currentPage(),
                'last_page' => $submissions->lastPage(),
                'total' => $submissions->total(),
            ]
        ]);
    }

    /**
     * Get all submissions by a specific student
     */
    public function studentSubmissions(Student $student)
    {
        $submissions = StudentSubmission::where('student_id', $student->id)
            ->with(['assignment.course', 'reviews'])
            ->latest()
            ->paginate(10);

        return response()->json([
            'status' => 200,
            'data' => StudentSubmissionResource::collection($submissions),
            'pagination' => [
                'current_page' => $submissions->currentPage(),
                'last_page' => $submissions->lastPage(),
                'total' => $submissions->total(),
            ]
        ]);
    }
}
