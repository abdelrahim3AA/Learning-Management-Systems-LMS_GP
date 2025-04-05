<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ComplaintSuggestionResource;
use App\Models\ComplaintSuggestion;
use Illuminate\Http\Request;

class ComplaintSuggestionController extends Controller
{
    /**
     * Display a listing of the complaints and suggestions.
     */
    public function index()
    {
        $complaintsSuggestions = ComplaintSuggestion::with('user')->paginate(10);

        return response()->json([
            'status' => 200,
            'data' => ComplaintSuggestionResource::collection($complaintsSuggestions),
            'pagination' => [
                'current_page' => $complaintsSuggestions->currentPage(),
                'last_page' => $complaintsSuggestions->lastPage(),
                'total' => $complaintsSuggestions->total(),
            ]
        ]);
    }

    /**
     * Store a newly created complaint or suggestion.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:complaint,suggestion',
            'message' => 'required|string',
        ]);

        $complaintSuggestion = ComplaintSuggestion::create([
            'user_id' => $request->user_id,
            'type' => $request->type,
            'message' => $request->message,
        ]);

        return response()->json([
            'status' => 201,
            'data' => new ComplaintSuggestionResource($complaintSuggestion),
        ], 201);
    }

    /**
     * Display the specified complaint or suggestion.
     */
    public function show(ComplaintSuggestion $complaintSuggestion)
    {
        return response()->json([
            'status' => 200,
            'data' => new ComplaintSuggestionResource($complaintSuggestion),
        ], 200);
    }

    /**
     * Update the specified complaint or suggestion.
     */
    public function update(Request $request, ComplaintSuggestion $complaintSuggestion)
    {
        $request->validate([
            'message' => 'required|string',
            'status' => 'required|in:pending,resolved',
        ]);

        $complaintSuggestion->update([
            'message' => $request->message,
            'status' => $request->status,
        ]);

        return response()->json([
            'status' => 200,
            'data' => new ComplaintSuggestionResource($complaintSuggestion),
        ], 200);
    }

    /**
     * Remove the specified complaint or suggestion.
     */
    public function destroy(ComplaintSuggestion $complaintSuggestion)
    {
        $complaintSuggestion->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Complaint or suggestion deleted successfully.',
        ], 200);
    }
}
