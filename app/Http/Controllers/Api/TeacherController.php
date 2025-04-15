<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TeacherResource;
use App\Models\Teacher;
use App\Models\Course;
use App\Models\Assignment;
use App\Models\StudentSubmission;
use App\Rules\UserRoleValidation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teachers = Teacher::with('user')->paginate(20);

        return response()->json([
            'status' => 200,
            'data' => TeacherResource::collection($teachers),
            'pagination' => [
                'current_page' => $teachers->currentPage(),
                'last_page' => $teachers->lastPage(),
                'total' => $teachers->total(),
            ]
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'user_id' => [
                'required',
                'exists:users,id',
                'unique:teachers,user_id',
                new UserRoleValidation('teacher')
            ],
            'qualification' => 'nullable|string|max:255',
            'bio' => 'nullable|string'
        ]);

        // Create Teacher
        $teacher = Teacher::create([
            'user_id' => $request->user_id,
            'qualification' => $request->qualification,
            'bio' => $request->bio
        ]);

        // Load user relationship
        $teacher->load('user');

        // Response
        return response()->json([
            'status' => 201,
            'message' => 'Teacher created successfully',
            'data' => new TeacherResource($teacher)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Teacher $teacher)
    {
        $teacher->load('user');

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
        // Validation
        $validatedData = $request->validate([
            'user_id' => [
                'sometimes',
                'required',
                'exists:users,id',
                Rule::unique('teachers', 'user_id')->ignore($teacher->id),
                new UserRoleValidation('teacher')
            ],
            'qualification' => 'nullable|string|max:255',
            'bio' => 'nullable|string'
        ]);

        // Update
        $teacher->update($validatedData);

        // Ensure we return the updated data
        $teacher->refresh();
        $teacher->load('user');

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
    public function destroy(Teacher $teacher)
    {
        // Delete the teacher
        $teacher->delete();

        // Response
        return response()->json([
            'status' => 200,
            'message' => 'Teacher deleted successfully'
        ], 200);
    }

    /**
     * Get teacher profile with user data
     */
    public function profile(Teacher $teacher)
    {
        $teacher->load('user');

        return response()->json([
            'status' => 200,
            'data' => new TeacherResource($teacher)
        ], 200);
    }

    /**
     * Get courses taught by the teacher
     */
    public function courses(Teacher $teacher)
    {
        $teacher->load('courses');

        return response()->json([
            'status' => 200,
            'data' => $teacher->courses
        ], 200);
    }

    /**
     * Get all assignments created by the teacher
     */
    public function assignments(Teacher $teacher)
    {
        $assignments = Assignment::where('teacher_id', $teacher->id)
            ->with('course')
            ->latest()
            ->paginate(10);

        return response()->json([
            'status' => 200,
            'data' => $assignments,
            'pagination' => [
                'current_page' => $assignments->currentPage(),
                'last_page' => $assignments->lastPage(),
                'total' => $assignments->total(),
            ]
        ], 200);
    }

    /**
     * Get pending submissions for review by the teacher
     */
    public function pendingReviews(Teacher $teacher)
    {
        // Get all courses taught by the teacher
        $courseIds = $teacher->courses->pluck('id');

        // Get all assignments from those courses
        $assignmentIds = Assignment::whereIn('course_id', $courseIds)
            ->orWhere('teacher_id', $teacher->id)
            ->pluck('id');

        // Get all submissions for those assignments that have not been reviewed
        $submissions = StudentSubmission::whereIn('assignment_id', $assignmentIds)
            ->whereDoesntHave('reviews')
            ->with(['student.user', 'assignment.course'])
            ->latest()
            ->paginate(10);

        return response()->json([
            'status' => 200,
            'data' => $submissions,
            'pagination' => [
                'current_page' => $submissions->currentPage(),
                'last_page' => $submissions->lastPage(),
                'total' => $submissions->total(),
            ]
        ], 200);
    }

    /**
     * Get teaching statistics for the teacher
     */
    public function statistics(Teacher $teacher)
    {
        // Get total courses
        $totalCourses = $teacher->courses->count();

        // Get total students enrolled in teacher's courses
        $totalStudents = 0;
        foreach ($teacher->courses as $course) {
            $totalStudents += $course->enrollments->count();
        }

        // Get total assignments
        $totalAssignments = Assignment::where('teacher_id', $teacher->id)->count();

        // Get total reviews
        $totalReviews = $teacher->assignmentReviews->count();

        // Get average review score
        $averageScore = $teacher->assignmentReviews->avg('score') ?? 0;

        return response()->json([
            'status' => 200,
            'data' => [
                'total_courses' => $totalCourses,
                'total_students' => $totalStudents,
                'total_assignments' => $totalAssignments,
                'total_reviews' => $totalReviews,
                'average_score' => round($averageScore, 2),
                'teacher' => new TeacherResource($teacher)
            ]
        ], 200);
    }

    /**
     * Assign a course to a teacher
     */
    public function assignCourse(Request $request, Teacher $teacher)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $course = Course::findOrFail($request->course_id);

        // Check if already assigned
        if ($course->teacher_id == $teacher->id) {
            return response()->json([
                'status' => 422,
                'message' => 'Teacher is already assigned to this course'
            ], 422);
        }

        // Assign teacher to course
        $course->teacher_id = $teacher->id;
        $course->save();

        return response()->json([
            'status' => 200,
            'message' => 'Course assigned successfully',
            'data' => $course
        ], 200);
    }

    /**
     * Remove a course from a teacher
     */
    public function removeCourse(Teacher $teacher, Course $course)
    {
        // Check if teacher is assigned to this course
        if ($course->teacher_id != $teacher->id) {
            return response()->json([
                'status' => 422,
                'message' => 'Teacher is not assigned to this course'
            ], 422);
        }

        // Remove teacher from course
        $course->teacher_id = null;
        $course->save();

        return response()->json([
            'status' => 200,
            'message' => 'Course removed successfully'
        ], 200);
    }
}
