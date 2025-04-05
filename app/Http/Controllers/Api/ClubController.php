<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClubResource;
use App\Models\Club;
use Illuminate\Http\Request;

class ClubController extends Controller
{
    /**
     * Get all clubs.
     */
    public function index()
    {
        $clubs = Club::latest()->paginate(10);

        return response()->json([
            'status' => 200,
            'data' => ClubResource::collection($clubs),
            'pagination' => [
                'current_page' => $clubs->currentPage(),
                'last_page' => $clubs->lastPage(),
                'total' => $clubs->total(),
            ]
        ]);
    }

    /**
     * Store a new club.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:clubs,name|max:255',
            'description' => 'nullable|string',
        ]);

        $club = Club::create($request->all());

        return response()->json([
            'status' => 201,
            'data' => new ClubResource($club),
        ], 201);
    }

    /**
     * Show a single club.
     */
    public function show(Club $club)
    {
        return response()->json([
            'status' => 200,
            'data' => new ClubResource($club),
        ], 200);
    }

    /**
     * Update a club.
     */
    public function update(Request $request, Club $club)
    {
        $request->validate([
            'name' => 'sometimes|string|unique:clubs,name,' . $club->id . '|max:255',
            'description' => 'sometimes|string',
        ]);

        $club->update($request->only('name', 'description'));

        return response()->json([
            'status' => 200,
            'message' => 'Club updated successfully.',
            'data' => new ClubResource($club),
        ], 200);
    }

    /**
     * Delete a club.
     */
    public function destroy(Club $club)
    {
        $club->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Club deleted successfully.',
        ], 200);
    }
}
