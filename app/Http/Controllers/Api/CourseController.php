<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CourseController extends Controller
{
    /**
     * Display a listing of the courses.
     */
    public function index()
    {
        $courses = Course::with('teacher.user')->get();

        return response()->json([
            'status' => 'success',
            'data' => CourseResource::collection($courses)
        ]);
    }

    /**
     * Display the specified course.
     */
    public function show(Course $course)
    {
        $course->load('teacher.user', 'lessons');

        return response()->json([
            'status' => 'success',
            'data' => $course
        ]);
    }

    /**
     * Get all lessons for a specific course.
     */
    public function lessons(Course $course)
    {
        $lessons = $course->lessons;

        return response()->json([
            'status' => 'success',
            'data' => $lessons
        ]);
    }
}