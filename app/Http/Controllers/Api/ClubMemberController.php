<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClubMemberResource;
use App\Models\ClubMember;
use Illuminate\Http\Request;

class ClubMemberController extends Controller
{
    /**
     * Get all club members.
     */
    public function index()
    {
        $clubMembers = ClubMember::latest()->paginate(10);

        return response()->json([
            'status' => 200,
            'data' => ClubMemberResource::collection($clubMembers),
            'pagination' => [
                'current_page' => $clubMembers->currentPage(),
                'last_page' => $clubMembers->lastPage(),
                'total' => $clubMembers->total(),
            ]
        ]);
    }

    /**
     * Store a new club member.
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'club_id' => 'required|exists:clubs,id',
        ]);

        $clubMember = ClubMember::create($request->all());

        return response()->json([
            'status' => 201,
            'data' => new ClubMemberResource($clubMember),
        ], 201);
    }

    /**
     * Show a single club member.
     */
    public function show(ClubMember $clubMember)
    {
        return response()->json([
            'status' => 200,
            'data' => new ClubMemberResource($clubMember),
        ], 200);
    }

    /**
     * Delete a club member.
     */
    public function destroy(ClubMember $clubMember)
    {
        $clubMember->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Club member removed successfully.',
        ], 200);
    }
}
