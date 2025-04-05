<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ParentTeacherConversationResource;
use App\Models\ParentTeacherConversation;
use Illuminate\Http\Request;

class ParentTeacherConversationController extends Controller
{
    /**
     * Get all conversations for a specific parent or teacher.
     */
    public function index(Request $request)
    {
        $conversations = ParentTeacherConversation::with('parent', 'teacher')
            ->paginate(10);

        return response()->json([
            'status' => 200,
            'data' => ParentTeacherConversationResource::collection($conversations),
            'pagination' => [
                'current_page' => $conversations->currentPage(),
                'last_page' => $conversations->lastPage(),
                'total' => $conversations->total(),
            ]
        ]);
    }

    /**
     * Store a new parent-teacher conversation.
     */
    public function store(Request $request)
    {
        $request->validate([
            'parent_id' => 'required|exists:users,id',
            'teacher_id' => 'required|exists:teachers,id',
        ]);

        $conversation = ParentTeacherConversation::create($request->all());

        return response()->json([
            'status' => 201,
            'data' => new ParentTeacherConversationResource($conversation),
        ], 201);
    }

    /**
     * Show a single parent-teacher conversation.
     */
    public function show(ParentTeacherConversation $conversation)
    {
        return response()->json([
            'status' => 200,
            'data' => new ParentTeacherConversationResource($conversation),
        ], 200);
    }

    /**
     * Delete a conversation.
     */
    public function destroy(ParentTeacherConversation $conversation)
    {
        $conversation->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Conversation deleted successfully.',
        ], 200);
    }
}
