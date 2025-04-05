<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClubChatMessageResource;
use App\Models\ClubChatMessage;
use Illuminate\Http\Request;

class ClubChatMessageController extends Controller
{
    /**
     * Get all messages for a specific club.
     */
    public function index(Request $request, $clubId)
    {
        $messages = ClubChatMessage::where('club_id', $clubId)
            ->latest()
            ->paginate(10);

        return response()->json([
            'status' => 200,
            'data' => ClubChatMessageResource::collection($messages),
            'pagination' => [
                'current_page' => $messages->currentPage(),
                'last_page' => $messages->lastPage(),
                'total' => $messages->total(),
            ]
        ]);
    }

    /**
     * Store a new chat message.
     */
    public function store(Request $request)
    {
        $request->validate([
            'club_id' => 'required|exists:clubs,id',
            'sender_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);

        $message = ClubChatMessage::create($request->all());

        return response()->json([
            'status' => 201,
            'data' => new ClubChatMessageResource($message),
        ], 201);
    }

    /**
     * Show a single chat message.
     */
    public function show(ClubChatMessage $clubChatMessage)
    {
        return response()->json([
            'status' => 200,
            'data' => new ClubChatMessageResource($clubChatMessage),
        ], 200);
    }

    /**
     * Delete a chat message.
     */
    public function destroy(ClubChatMessage $clubChatMessage)
    {
        $clubChatMessage->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Chat message deleted successfully.',
        ], 200);
    }
}
