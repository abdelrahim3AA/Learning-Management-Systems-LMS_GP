<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Http\Resources\ExamResource;
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
            'title' => 'required|string|max:255',
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
            'title' => 'required|string|max:255',
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
}
