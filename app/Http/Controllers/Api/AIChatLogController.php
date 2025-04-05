<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AIChatLogResource;
use App\Models\AIChatLog;
use Illuminate\Http\Request;

class AIChatLogController extends Controller
{
    /**
     * Display a listing of the AI chat logs.
     */
    public function index()
    {
        $chatLogs = AIChatLog::with('user')->paginate(10);

        return response()->json([
            'status' => 200,
            'data' => AIChatLogResource::collection($chatLogs),
            'pagination' => [
                'current_page' => $chatLogs->currentPage(),
                'last_page' => $chatLogs->lastPage(),
                'total' => $chatLogs->total(),
            ]
        ]);
    }

    /**
     * Store a newly created chat log.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);

        $chatLog = AIChatLog::create([
            'user_id' => $request->user_id,
            'message' => $request->message,
        ]);

        return response()->json([
            'status' => 201,
            'data' => new AIChatLogResource($chatLog),
        ], 201);
    }

    /**
     * Display the specified AI chat log.
     */
    public function show(AIChatLog $aiChatLog)
    {
        return response()->json([
            'status' => 200,
            'data' => new AIChatLogResource($aiChatLog),
        ], 200);
    }

    /**
     * Update the specified AI chat log.
     */
    public function update(Request $request, AIChatLog $aiChatLog)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $aiChatLog->update([
            'message' => $request->message,
        ]);

        return response()->json([
            'status' => 200,
            'data' => new AIChatLogResource($aiChatLog),
        ], 200);
    }

    /**
     * Remove the specified AI chat log.
     */
    public function destroy(AIChatLog $aiChatLog)
    {
        $aiChatLog->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Chat log deleted successfully.',
        ], 200);
    }
}
