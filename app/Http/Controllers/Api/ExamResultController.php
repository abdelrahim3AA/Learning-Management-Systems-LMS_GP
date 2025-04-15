<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ExamResultResource;
use App\Models\ExamResult;
use Illuminate\Http\Request;

class ExamResultController extends Controller
{
    public function index()
    {
        return ExamResultResource::collection(ExamResult::with(['student', 'exam'])->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id'  => 'required|exists:students,id',
            'exam_id'     => 'required|exists:exams,id',
            'score'       => 'required|numeric',
            'total_marks' => 'required|numeric',
        ]);

        $examResult = ExamResult::create($data);

        return new ExamResultResource($examResult);
    }

    public function show(ExamResult $examResult)
    {
        return new ExamResultResource($examResult->load(['student', 'exam']));
    }

    public function update(Request $request, ExamResult $examResult)
    {
        $data = $request->validate([
            'score'       => 'nullable|numeric',
            'total_marks' => 'nullable|numeric',
        ]);

        $examResult->update($data);

        return new ExamResultResource($examResult);
    }

    public function destroy(ExamResult $examResult)
    {
        $examResult->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }

    /**
     * Get results by student ID
     */
    public function getResultsByStudentId($studentId)
    {
        $results = ExamResult::where('student_id', $studentId)
            ->with(['student', 'exam'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 200,
            'data'   => ExamResultResource::collection($results),
        ]);
    }

    /**
     * Get results by exam ID
     */
    public function getResultsByExamId($examId)
    {
        $results = ExamResult::where('exam_id', $examId)
            ->with(['student', 'exam'])
            ->orderBy('score', 'desc')
            ->get();

        return response()->json([
            'status' => 200,
            'data'   => ExamResultResource::collection($results),
        ]);
    }

    /**
     * Get exam statistics
     */
    public function getExamStatistics($examId)
    {
        $results = ExamResult::where('exam_id', $examId)->get();

        $statistics = [
            'total_students' => $results->count(),
            'average_score'  => $results->avg('score'),
            'highest_score'  => $results->max('score'),
            'lowest_score'   => $results->min('score'),
            'pass_count'     => $results->where('score', '>=', 50)->count(), // Assuming 50 is pass mark
            'fail_count'     => $results->where('score', '<', 50)->count(),
        ];

        return response()->json([
            'status' => 200,
            'data'   => $statistics,
        ]);
    }

    /**
     * Get student's performance summary
     */
    public function getStudentPerformanceSummary($studentId)
    {
        $results = ExamResult::where('student_id', $studentId)
            ->with('exam')
            ->get();

        $summary = [
            'total_exams'   => $results->count(),
            'average_score' => $results->avg('score'),
            'highest_score' => $results->max('score'),
            'lowest_score'  => $results->min('score'),
            'passed_exams'  => $results->where('score', '>=', 50)->count(),
            'failed_exams'  => $results->where('score', '<', 50)->count(),
        ];

        return response()->json([
            'status' => 200,
            'data'   => $summary,
        ]);
    }

    /**
     * Get top performers for an exam
     */
    public function getTopPerformers($examId, $limit = 10)
    {
        $results = ExamResult::where('exam_id', $examId)
            ->with(['student', 'exam'])
            ->orderBy('score', 'desc')
            ->take($limit)
            ->get();

        return response()->json([
            'status' => 200,
            'data'   => ExamResultResource::collection($results),
        ]);
    }

    /**
     * Get student's recent results
     */
    public function getStudentRecentResults($studentId, $limit = 5)
    {
        $results = ExamResult::where('student_id', $studentId)
            ->with(['exam'])
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();

        return response()->json([
            'status' => 200,
            'data'   => ExamResultResource::collection($results),
        ]);
    }
}
