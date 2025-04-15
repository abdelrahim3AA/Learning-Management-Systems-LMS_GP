<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuestionResource;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Display a listing of questions.
     */
    public function index()
    {
        $questions = Question::all();
        return response()->json($questions);
    }

    public function store(Request $request)
    {
        $question = Question::create($request->all());
        return response()->json($question);
    }

    public function show(Question $question)
    {
        return response()->json($question);
    }

    public function update(Request $request, Question $question)
    {
        $question->update($request->all());
        return response()->json($question);
    }

    public function destroy(Question $question)
    {
        $question->delete();
        return response()->json(null, 204);
    }

    public function getQuestionsByCourseId($courseId)
    {
        $questions = Question::where('course_id', $courseId)->get();
        return response()->json($questions);
    }

    public function getQuestionsByAssignmentId($assignmentId)
    {
        $questions = Question::where('assignment_id', $assignmentId)->get();
        return response()->json($questions);
    }

    public function getQuestionsByStudentId($studentId)
    {
        $questions = Question::where('student_id', $studentId)->get();
        return response()->json($questions);
    }

    public function getQuestionsByTeacherId($teacherId)
    {
        $questions = Question::where('teacher_id', $teacherId)->get();
        return response()->json($questions);
    }

}
