<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ParentTeacherMessageResource;
use App\Models\ParentTeacherMessage;
use Illuminate\Http\Request;

class ParentTeacherMessageController extends Controller
{
    /**
     * Get all messages for a specific conversation.
     */
    public function index($conversationId)
    {
        $messages = ParentTeacherMessage::where('conversation_id', $conversationId)
            ->with('sender', 'conversation')
            ->paginate(10);

        return response()->json([
            'status' => 200,
            'data' => ParentTeacherMessageResource::collection($messages),
            'pagination' => [
                'current_page' => $messages->currentPage(),
                'last_page' => $messages->lastPage(),
                'total' => $messages->total(),
            ]
        ]);
    }

    /**
     * Store a new message in a conversation.
     */
    public function store(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:parent_teacher_conversations,id',
            'sender_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);

        $message = ParentTeacherMessage::create($request->all());

        return response()->json([
            'status' => 201,
            'data' => new ParentTeacherMessageResource($message),
        ], 201);
    }

    /**
     * Show a single parent-teacher message.
     */
    public function show(ParentTeacherMessage $message)
    {
        return response()->json([
            'status' => 200,
            'data' => new ParentTeacherMessageResource($message),
        ], 200);
    }

    /**
     * Delete a message from the conversation.
     */
    public function destroy(ParentTeacherMessage $message)
    {
        $message->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Message deleted successfully.',
        ], 200);
    }
}
