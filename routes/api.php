<?php

use App\Http\Controllers\Api\AIChatLogController;
use App\Http\Controllers\Api\AssignmentController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\ExamController;
use App\Http\Controllers\Api\ExamResultController;
use App\Http\Controllers\Api\LessonController;
use App\Http\Controllers\Api\LessonProgressController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\QuestionOptionController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\TeacherAssignmentReviewController;
use App\Http\Controllers\Api\TeacherController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\AuthController;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['message' => 'Welcome to API'], 200);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
// Sanctum
Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::apiResource('users', UserController::class);

Route::apiResource('students', StudentController::class);
// Additional student-specific functionalities
Route::prefix('students/{student}')->group(function () {
    // Profile and courses
    Route::get('/profile', [StudentController::class, 'profile']);
    Route::get('/courses', [StudentController::class, 'courses']);

    // Course enrollment
    Route::post('/courses/{course}/enroll', [StudentController::class, 'enroll']);
    Route::delete('/courses/{course}/unenroll', [StudentController::class, 'unenroll']);

    // Lesson progress
    Route::get('/lessons/{lesson}/progress', [StudentController::class, 'lessonProgress']);
    Route::post('/lessons/{lesson}/progress', [StudentController::class, 'updateProgress']);
    Route::get('/progress', [StudentController::class, 'allProgress']);
});

// Teacher Routes
Route::apiResource('teachers', TeacherController::class);

Route::prefix('teachers')->group(function () {
    // Additional Teacher Routes
    Route::get('/{teacher}/profile', [TeacherController::class, 'profile']);
    Route::get('/{teacher}/courses', [TeacherController::class, 'courses']);
    Route::get('/{teacher}/assignments', [TeacherController::class, 'assignments']);
    Route::get('/{teacher}/pending-reviews', [TeacherController::class, 'pendingReviews']);
    Route::get('/{teacher}/statistics', [TeacherController::class, 'statistics']);
    Route::post('/{teacher}/assign-course', [TeacherController::class, 'assignCourse']);
    Route::delete('/{teacher}/courses/{course}', [TeacherController::class, 'removeCourse']);
});

Route::apiResource('courses', CourseController::class);
Route::get('/courses/{course}/lessons', [CourseController::class, 'lessons']);

// Lesson Routes
Route::prefix('lessons')->group(function () {
    // Basic CRUD routes
    Route::get('/', [LessonController::class, 'index']);
    Route::post('/', [LessonController::class, 'store']);
    Route::get('/{lesson}', [LessonController::class, 'show']);
    Route::put('/{lesson}', [LessonController::class, 'update']);
    Route::delete('/{lesson}', [LessonController::class, 'destroy']);

    // Additional routes
    Route::get('/course/{courseId}/all', [LessonController::class, 'getLessonsByCourseId']);
    Route::get('/{lesson}/next', [LessonController::class, 'getNextLesson']);
    Route::get('/{lesson}/previous', [LessonController::class, 'getPreviousLesson']);
    Route::get('/latest/all', [LessonController::class, 'getLatestLessons']);
});

Route::apiResource('lesson-progress', LessonProgressController::class);

// Assignment Routes
Route::prefix('assignments')->group(function () {
    Route::get('/', [AssignmentController::class, 'index']);
    Route::post('/', [AssignmentController::class, 'store']);
    Route::get('/{assignment}', [AssignmentController::class, 'show']);
    Route::put('/{assignment}', [AssignmentController::class, 'update']);
    Route::delete('/{assignment}', [AssignmentController::class, 'destroy']);

    // Additional Assignment Routes
    Route::post('/{assignment}/publish', [AssignmentController::class, 'publish']);
    Route::get('/{assignment}/statistics', [AssignmentController::class, 'statistics']);

    // Course Assignments
    Route::get('/course/{course}', [AssignmentController::class, 'courseAssignments']);

    // Teacher Assignments
    Route::get('/teacher/{teacher}', [AssignmentController::class, 'teacherAssignments']);

    // Student Assignments
    Route::get('/student/{student}', [AssignmentController::class, 'studentAssignments']);
});

// AI Chat Log Routes
Route::middleware('auth:sanctum')->group(function () {
    // Get all chat logs
    Route::get('/chat-logs', [AIChatLogController::class, 'index']);

    // Send a message and get AI response
    Route::post('/chat-logs', [AIChatLogController::class, 'store']);

    // Get chat history for a specific user
    Route::get('/chat-logs/user/{userId}', [AIChatLogController::class, 'userHistory']);

    // Get a specific chat log
    Route::get('/chat-logs/{aiChatLog}', [AIChatLogController::class, 'show']);

    // Update a specific chat log
    Route::put('/chat-logs/{aiChatLog}', [AIChatLogController::class, 'update']);
    Route::patch('/chat-logs/{aiChatLog}', [AIChatLogController::class, 'update']);

    // Delete a specific chat log
    Route::delete('/chat-logs/{aiChatLog}', [AIChatLogController::class, 'destroy']);
});

// Teacher Assignment Review Routes
Route::prefix('teacher-assignment-reviews')->group(function () {
    Route::get('/', [TeacherAssignmentReviewController::class, 'index']);
    Route::post('/', [TeacherAssignmentReviewController::class, 'store']);
    Route::get('/{teacherAssignmentReview}', [TeacherAssignmentReviewController::class, 'show']);
    Route::put('/{teacherAssignmentReview}', [TeacherAssignmentReviewController::class, 'update']);
    Route::delete('/{teacherAssignmentReview}', [TeacherAssignmentReviewController::class, 'destroy']);

    // Additional filter routes
    Route::get('/by-teacher/{teacherId}', [TeacherAssignmentReviewController::class, 'getReviewsByTeacherId']);
    Route::get('/by-student/{studentId}', [TeacherAssignmentReviewController::class, 'getReviewsByStudentId']);
    Route::get('/by-course/{courseId}', [TeacherAssignmentReviewController::class, 'getReviewsByCourseId']);
    Route::get('/by-assignment/{assignmentId}', [TeacherAssignmentReviewController::class, 'getReviewsByAssignmentId']);
    Route::get('/by-submission/{submissionId}', [TeacherAssignmentReviewController::class, 'getReviewsBySubmissionId']);
});

// Question Routes
Route::prefix('questions')->group(function () {
    // Basic CRUD routes
    Route::get('/', [QuestionController::class, 'index']);
    Route::post('/', [QuestionController::class, 'store']);
    Route::get('/{question}', [QuestionController::class, 'show']);
    Route::put('/{question}', [QuestionController::class, 'update']);
    Route::delete('/{question}', [QuestionController::class, 'destroy']);

    // Filter routes
    Route::get('/by-course/{courseId}', [QuestionController::class, 'getQuestionsByCourseId']);
    Route::get('/by-assignment/{assignmentId}', [QuestionController::class, 'getQuestionsByAssignmentId']);
    Route::get('/by-student/{studentId}', [QuestionController::class, 'getQuestionsByStudentId']);
    Route::get('/by-teacher/{teacherId}', [QuestionController::class, 'getQuestionsByTeacherId']);
});

// Question Options Routes
Route::prefix('question-options')->group(function () {
    // Basic CRUD routes
    Route::get('/', [QuestionOptionController::class, 'index']);
    Route::post('/', [QuestionOptionController::class, 'store']);
    Route::get('/{option}', [QuestionOptionController::class, 'show']);
    Route::put('/{option}', [QuestionOptionController::class, 'update']);
    Route::delete('/{option}', [QuestionOptionController::class, 'destroy']);

    // Additional routes
    Route::get('/question/{questionId}/all', [QuestionOptionController::class, 'getOptionsByQuestionId']);
    Route::get('/question/{questionId}/correct', [QuestionOptionController::class, 'getCorrectOptionsByQuestionId']);
    Route::get('/correct/all', [QuestionOptionController::class, 'getAllCorrectOptions']);
});

// Exam Routes
Route::prefix('exams')->group(function () {
    // Basic CRUD routes
    Route::get('/', [ExamController::class, 'index']);
    Route::post('/', [ExamController::class, 'store']);
    Route::get('/{exam}', [ExamController::class, 'show']);
    Route::put('/{exam}', [ExamController::class, 'update']);
    Route::delete('/{exam}', [ExamController::class, 'destroy']);

    // Additional routes
    Route::get('/course/{courseId}/all', [ExamController::class, 'getExamsByCourseId']);
    Route::get('/upcoming/all', [ExamController::class, 'getUpcomingExams']);
    Route::get('/past/all', [ExamController::class, 'getPastExams']);
    Route::get('/today/all', [ExamController::class, 'getTodayExams']);
    Route::post('/date-range', [ExamController::class, 'getExamsByDateRange']);
});

// Exam Results Routes
Route::prefix('exam-results')->group(function () {
    // Basic CRUD routes
    Route::get('/', [ExamResultController::class, 'index']);
    Route::post('/', [ExamResultController::class, 'store']);
    Route::get('/{examResult}', [ExamResultController::class, 'show']);
    Route::put('/{examResult}', [ExamResultController::class, 'update']);
    Route::delete('/{examResult}', [ExamResultController::class, 'destroy']);

    // Student-specific routes
    Route::get('/student/{studentId}/all', [ExamResultController::class, 'getResultsByStudentId']);
    Route::get('/student/{studentId}/performance', [ExamResultController::class, 'getStudentPerformanceSummary']);
    Route::get('/student/{studentId}/recent', [ExamResultController::class, 'getStudentRecentResults']);

    // Exam-specific routes
    Route::get('/exam/{examId}/all', [ExamResultController::class, 'getResultsByExamId']);
    Route::get('/exam/{examId}/statistics', [ExamResultController::class, 'getExamStatistics']);
    Route::get('/exam/{examId}/top-performers', [ExamResultController::class, 'getTopPerformers']);
});

RateLimiter::for('api', function (Request $request) {
    return Limit::perMinute(60);
});
