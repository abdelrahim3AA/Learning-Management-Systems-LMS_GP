<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ExamResource;
use App\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExamController extends Controller
{
    // Display a listing of exams
    public function index()
    {
        $exams = Exam::with('course')->get(); // Include course details in the response
        return ExamResource::collection($exams);
    }

    // Store a newly created exam
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
            'title'     => 'required|string|max:255',
            'exam_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $exam = Exam::create($request->all());
        return new ExamResource($exam);
    }

    // Display the specified exam
    public function show($id)
    {
        $exam = Exam::with('course')->findOrFail($id);
        return new ExamResource($exam);
    }

    // Update the specified exam
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
            'title'     => 'required|string|max:255',
            'exam_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $exam = Exam::findOrFail($id);
        $exam->update($request->all());
        return new ExamResource($exam);
    }

    // Remove the specified exam
    public function destroy($id)
    {
        $exam = Exam::findOrFail($id);
        $exam->delete();
        return response()->json(null, 204);
    }

    /**
     * Get exams by course ID
     */
    public function getExamsByCourseId($courseId)
    {
        $exams = Exam::where('course_id', $courseId)
            ->with('course')
            ->orderBy('exam_date', 'asc')
            ->get();

        return response()->json([
            'status' => 200,
            'data'   => ExamResource::collection($exams),
        ]);
    }

    /**
     * Get upcoming exams
     */
    public function getUpcomingExams()
    {
        $exams = Exam::where('exam_date', '>=', now())
            ->with('course')
            ->orderBy('exam_date', 'asc')
            ->get();

        return response()->json([
            'status' => 200,
            'data'   => ExamResource::collection($exams),
        ]);
    }

    /**
     * Get past exams
     */
    public function getPastExams()
    {
        $exams = Exam::where('exam_date', '<', now())
            ->with('course')
            ->orderBy('exam_date', 'desc')
            ->get();

        return response()->json([
            'status' => 200,
            'data'   => ExamResource::collection($exams),
        ]);
    }

    /**
     * Get exams by date range
     */
    public function getExamsByDateRange(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors(),
            ], 422);
        }

        $exams = Exam::whereBetween('exam_date', [$request->start_date, $request->end_date])
            ->with('course')
            ->orderBy('exam_date', 'asc')
            ->get();

        return response()->json([
            'status' => 200,
            'data'   => ExamResource::collection($exams),
        ]);
    }

    /**
     * Get today's exams
     */
    public function getTodayExams()
    {
        $exams = Exam::whereDate('exam_date', today())
            ->with('course')
            ->orderBy('exam_date', 'asc')
            ->get();

        return response()->json([
            'status' => 200,
            'data'   => ExamResource::collection($exams),
        ]);
    }
}
