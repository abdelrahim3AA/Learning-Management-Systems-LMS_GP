<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AssignmentResource;
use App\Models\Assignment;
use App\Models\Course;
use App\Models\Student;
use App\Models\StudentSubmission;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $assignments = Assignment::with(['course', 'teacher'])->paginate(10);

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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'course_id' => 'required|exists:courses,id',
            'teacher_id' => 'required|exists:teachers,id',
            'due_date' => 'required|date|after:now',
            'max_score' => 'required|integer|min:1',
            'file_path' => 'nullable|file|max:10240', // 10MB max
            'status' => ['required', Rule::in(['draft', 'published'])]
        ]);

        // Handle file upload if present
        $filePath = null;
        if ($request->hasFile('file_path')) {
            $filePath = $request->file('file_path')->store('assignments', 'public');
        }

        // Create assignment
        $assignment = Assignment::create([
            'title' => $request->title,
            'description' => $request->description,
            'course_id' => $request->course_id,
            'teacher_id' => $request->teacher_id,
            'due_date' => $request->due_date,
            'max_score' => $request->max_score,
            'file_path' => $filePath ? Storage::url($filePath) : null,
            'status' => $request->status
        ]);

        return response()->json([
            'status' => 201,
            'data' => new AssignmentResource($assignment)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Assignment $assignment)
    {
        $assignment->load(['course', 'teacher.user']);

        return response()->json([
            'status' => 200,
            'data' => new AssignmentResource($assignment),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Assignment $assignment)
    {
        // Validation
        $validatedData = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'course_id' => 'sometimes|required|exists:courses,id',
            'teacher_id' => 'sometimes|required|exists:teachers,id',
            'due_date' => 'sometimes|required|date|after:now',
            'max_score' => 'sometimes|required|integer|min:1',
            'file_path' => 'nullable|file|max:10240', // 10MB max
            'status' => ['sometimes', 'required', Rule::in(['draft', 'published'])]
        ]);

        // Handle file upload if present
        if ($request->hasFile('file_path')) {
            // Delete old file if exists
            if ($assignment->file_path) {
                $oldPath = str_replace('/storage/', '', $assignment->file_path);
                Storage::disk('public')->delete($oldPath);
            }

            $filePath = $request->file('file_path')->store('assignments', 'public');
            $validatedData['file_path'] = Storage::url($filePath);
        }

        // Update assignment
        $assignment->update($validatedData);

        // Reload the assignment to get fresh data
        $assignment->load(['course', 'teacher.user']);

        return response()->json([
            'status' => 200,
            'data' => new AssignmentResource($assignment)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Assignment $assignment)
    {
        // Delete file if exists
        if ($assignment->file_path) {
            $path = str_replace('/storage/', '', $assignment->file_path);
            Storage::disk('public')->delete($path);
        }

        $assignment->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Assignment was successfully deleted'
        ], 200);
    }

    /**
     * Get all assignments for a specific course
     */
    public function courseAssignments(Course $course)
    {
        $assignments = Assignment::where('course_id', $course->id)
            ->where('status', 'published')
            ->with('teacher.user')
            ->paginate(10);

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
     * Get all assignments created by a teacher
     */
    public function teacherAssignments(Teacher $teacher)
    {
        $assignments = Assignment::where('teacher_id', $teacher->id)
            ->with(['course'])
            ->paginate(10);

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
     * Get all assignments for a student (from enrolled courses)
     */
    public function studentAssignments(Student $student)
    {
        // Get all courses the student is enrolled in
        $courseIds = $student->enrollments->pluck('course_id');

        // Get all published assignments from those courses
        $assignments = Assignment::whereIn('course_id', $courseIds)
            ->where('status', 'published')
            ->with(['course', 'teacher.user'])
            ->paginate(10);

        // Add submission status for each assignment
        $assignmentsWithStatus = AssignmentResource::collection($assignments)
            ->map(function ($assignment) use ($student) {
                $submission = StudentSubmission::where('assignment_id', $assignment->id)
                    ->where('student_id', $student->id)
                    ->first();

                $assignment->additional([
                    'submission_status' => $submission ? $submission->status : 'not_submitted',
                    'submission_id' => $submission ? $submission->id : null,
                    'submitted_at' => $submission ? $submission->created_at : null,
                ]);

                return $assignment;
            });

        return response()->json([
            'status' => 200,
            'data' => $assignmentsWithStatus,
            'pagination' => [
                'current_page' => $assignments->currentPage(),
                'last_page' => $assignments->lastPage(),
                'total' => $assignments->total(),
            ]
        ]);
    }

    /**
     * Publish a draft assignment
     */
    public function publish(Assignment $assignment)
    {
        if ($assignment->status === 'published') {
            return response()->json([
                'status' => 422,
                'message' => 'Assignment is already published'
            ], 422);
        }

        $assignment->update(['status' => 'published']);

        return response()->json([
            'status' => 200,
            'message' => 'Assignment published successfully',
            'data' => new AssignmentResource($assignment)
        ], 200);
    }

    /**
     * Get assignment statistics for a teacher
     */
    public function statistics(Assignment $assignment)
    {
        // Ensure the assignment exists and is loaded with necessary relations
        $assignment->load('course.enrollments.student');

        // Get total number of students enrolled in the course
        $totalStudents = $assignment->course->enrollments->count();

        // Get submissions count
        $submissions = StudentSubmission::where('assignment_id', $assignment->id)->get();
        $submittedCount = $submissions->count();

        // Get reviewed submissions count
        $reviewedCount = $submissions->filter(function($submission) {
            return $submission->reviews->count() > 0;
        })->count();

        // Calculate average score from reviewed submissions
        $averageScore = 0;
        if ($reviewedCount > 0) {
            $totalScore = $submissions->sum(function($submission) {
                return $submission->reviews->avg('score') ?? 0;
            });
            $averageScore = $totalScore / $reviewedCount;
        }

        return response()->json([
            'status' => 200,
            'data' => [
                'assignment' => new AssignmentResource($assignment),
                'total_students' => $totalStudents,
                'submitted_count' => $submittedCount,
                'submission_rate' => $totalStudents > 0 ? ($submittedCount / $totalStudents) * 100 : 0,
                'reviewed_count' => $reviewedCount,
                'review_rate' => $submittedCount > 0 ? ($reviewedCount / $submittedCount) * 100 : 0,
                'average_score' => round($averageScore, 2),
            ]
        ], 200);
    }
}
