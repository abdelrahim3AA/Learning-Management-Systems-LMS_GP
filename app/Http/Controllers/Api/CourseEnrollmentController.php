<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CourseEnrollmentResource;
use App\Models\CourseEnrollment;
use Illuminate\Http\Request;

class CourseEnrollmentController extends Controller
{
    public function index()
    {
        $enrollments = CourseEnrollment::paginate(10);
        return response()->json([
            'status' => 200,
            'data' => CourseEnrollmentResource::collection($enrollments),
            'pagination' => [
                'current_page' => $enrollments->currentPage(),
                'last_page' => $enrollments->lastPage(),
                'total' => $enrollments->total(),
            ]
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id'
        ]);

        $enrollment = CourseEnrollment::create([
            'student_id' => $request->student_id,
            'course_id' => $request->course_id,
            'enrolled_at' => now()
        ]);

        return response()->json([
            'status' => 201,
            'data' => new CourseEnrollmentResource($enrollment)
        ], 201);
    }

    public function show(CourseEnrollment $enrollment)
    {
        return response()->json([
            'status' => 200,
            'data' => new CourseEnrollmentResource($enrollment)
        ]);
    }

    public function destroy(CourseEnrollment $enrollment)
    {
        $enrollment->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Enrollment deleted successfully'
        ]);
    }
}
