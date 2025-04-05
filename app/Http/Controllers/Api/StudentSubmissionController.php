<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\StudentSubmissionResource;
use App\Models\StudentSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentSubmissionController extends Controller
{
    /**
     * Display a listing of student submissions.
     */
    public function index()
    {
        $submissions = StudentSubmission::with(['student', 'assignment'])->latest()->paginate(10);

        return response()->json([
            'status' => 200,
            'data' => StudentSubmissionResource::collection($submissions),
            'pagination' => [
                'current_page' => $submissions->currentPage(),
                'last_page' => $submissions->lastPage(),
                'total' => $submissions->total(),
            ]
        ]);
    }

    /**
     * Store a newly created student submission.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'assignment_id' => 'required|exists:assignments,id',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'submission_text' => 'nullable|string',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('submissions', 'public');
        }

        $submission = StudentSubmission::create([
            'student_id' => $request->student_id,
            'assignment_id' => $request->assignment_id,
            'file_path' => $filePath,
            'submission_text' => $request->submission_text,
        ]);

        return response()->json([
            'status' => 201,
            'data' => new StudentSubmissionResource($submission),
        ], 201);
    }

    /**
     * Display the specified student submission.
     */
    public function show(StudentSubmission $studentSubmission)
    {
        return response()->json([
            'status' => 200,
            'data' => new StudentSubmissionResource($studentSubmission),
        ], 200);
    }

    /**
     * Update the specified student submission.
     */
    public function update(Request $request, StudentSubmission $studentSubmission)
    {
        $request->validate([
            'submission_text' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        if ($request->hasFile('file')) {
            if ($studentSubmission->file_path) {
                Storage::disk('public')->delete($studentSubmission->file_path);
            }
            $filePath = $request->file('file')->store('submissions', 'public');
            $studentSubmission->update(['file_path' => $filePath]);
        }

        $studentSubmission->update($request->only('submission_text'));

        return response()->json([
            'status' => 200,
            'message' => 'Submission updated successfully.',
            'data' => new StudentSubmissionResource($studentSubmission),
        ], 200);
    }

    /**
     * Delete the specified student submission.
     */
    public function destroy(StudentSubmission $studentSubmission)
    {
        if ($studentSubmission->file_path) {
            Storage::disk('public')->delete($studentSubmission->file_path);
        }

        $studentSubmission->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Submission deleted successfully.',
        ], 200);
    }
}
