<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\StudentResource;
use App\Models\Student;
use App\Rules\UserRoleValidation;
use Illuminate\Http\Request;
use App\Rules\ParentRole;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::paginate(10);

        return response()->json([
            'status' => 200,
            'data' => StudentResource::collection($students),
            'pagination' => [
                'current_page' => $students->currentPage(),
                'last_page' => $students->lastPage(),
                'total' => $students->total(),
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'student_id' => ['required', 'exists:users,id', 'unique:students,student_id', new UserRoleValidation('student')],
            'parent_id' => ['nullable', 'exists:users,id', new UserRoleValidation('parent')],
            'grade_level' => 'required|string|max:50'
        ]);
        // Store
        $student = Student::create([
            'student_id' => $request->student_id,
            'parent_id' => $request->parent_id,
            'grade_level' => $request->grade_level
        ]);
        // Redirect
        return response()->json([
            'status' => 201,
            'data' => new StudentResource($student)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        return response()->json([
            'status' => 200,
            'data' => new StudentResource($student),
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        // Validation
        $validatedData = $request->validate([
            'student_id' => [
                'required',
                'exists:users,id',
                Rule::unique('students', 'student_id')->ignore($student->id), // Ignore the current student
                new UserRoleValidation('student')
            ],
            'parent_id' => [
                'nullable',
                'exists:users,id',
                new UserRoleValidation('parent')
            ],
            'grade_level' => 'required|string|max:50'
        ]);

        // Update student record
        $student->update($validatedData);

        // Return response
        return response()->json([
            'status' => 200,
            'data' => new StudentResource($student)
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {

        $student->delete();

        return response()->json([
            'status' => 200,
            'message' => 'The student was successfully deleted'
        ], 200);
    }

}
