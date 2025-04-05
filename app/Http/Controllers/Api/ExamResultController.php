<?php

namespace App\Http\Controllers;

use App\Models\ExamResult;
use App\Http\Resources\ExamResultResource;
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
            'student_id' => 'required|exists:students,id',
            'exam_id' => 'required|exists:exams,id',
            'score' => 'required|numeric',
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
            'score' => 'nullable|numeric',
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
}
