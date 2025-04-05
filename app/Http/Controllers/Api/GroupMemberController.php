<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GroupMemberResource;
use App\Models\GroupMember;
use Illuminate\Http\Request;

class GroupMemberController extends Controller
{
    public function index()
    {
        $groupMembers = GroupMember::with(['student', 'group'])->paginate(10);

        return response()->json([
            'status' => 200,
            'data' => GroupMemberResource::collection($groupMembers),
            'pagination' => [
                'current_page' => $groupMembers->currentPage(),
                'last_page' => $groupMembers->lastPage(),
                'total' => $groupMembers->total(),
            ]
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'group_id' => 'required|exists:groups,id',
        ]);

        $groupMember = GroupMember::create($request->all());

        return response()->json([
            'status' => 201,
            'data' => new GroupMemberResource($groupMember)
        ], 201);
    }

    public function show(GroupMember $groupMember)
    {
        return response()->json([
            'status' => 200,
            'data' => new GroupMemberResource($groupMember),
        ], 200);
    }

    public function update(Request $request, GroupMember $groupMember)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'group_id' => 'required|exists:groups,id',
        ]);

        $groupMember->update($request->all());

        return response()->json([
            'status' => 200,
            'data' => new GroupMemberResource($groupMember)
        ], 200);
    }


    public function destroy(GroupMember $groupMember)
    {
        $groupMember->delete();

        return response()->json([
            'status' => 200,
            'message' => 'The group member was successfully deleted'
        ], 200);
    }
}
