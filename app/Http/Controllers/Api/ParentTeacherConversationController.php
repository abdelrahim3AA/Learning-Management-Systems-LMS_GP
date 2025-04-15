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
            'status'     => 200,
            'data'       => ParentTeacherConversationResource::collection($conversations),
            'pagination' => [
                'current_page' => $conversations->currentPage(),
                'last_page'    => $conversations->lastPage(),
                'total'        => $conversations->total(),
            ],
        ]);
    }

    /**
     * Store a new parent-teacher conversation.
     */
    public function store(Request $request)
    {
        $request->validate([
            'parent_id'  => 'required|exists:users,id',
            'teacher_id' => 'required|exists:teachers,id',
        ]);

        $conversation = ParentTeacherConversation::create($request->all());

        return response()->json([
            'status' => 201,
            'data'   => new ParentTeacherConversationResource($conversation),
        ], 201);
    }

    /**
     * Show a single parent-teacher conversation.
     */
    public function show(ParentTeacherConversation $conversation)
    {
        return response()->json([
            'status' => 200,
            'data'   => new ParentTeacherConversationResource($conversation),
        ], 200);
    }

    /**
     * Delete a conversation.
     */
    public function destroy(ParentTeacherConversation $conversation)
    {
        $conversation->delete();

        return response()->json([
            'status'  => 200,
            'message' => 'Conversation deleted successfully.',
        ], 200);
    }

    /**
     * Get conversations for a specific parent
     */
    public function getParentConversations($parentId)
    {
        $conversations = ParentTeacherConversation::where('parent_id', $parentId)
            ->with(['teacher', 'messages'])
            ->latest()
            ->get();

        return response()->json([
            'status' => 200,
            'data'   => ParentTeacherConversationResource::collection($conversations),
        ]);
    }

    /**
     * Get conversations for a specific teacher
     */
    public function getTeacherConversations($teacherId)
    {
        $conversations = ParentTeacherConversation::where('teacher_id', $teacherId)
            ->with(['parent', 'messages'])
            ->latest()
            ->get();

        return response()->json([
            'status' => 200,
            'data'   => ParentTeacherConversationResource::collection($conversations),
        ]);
    }

    /**
     * Mark conversation as read
     */
    public function markAsRead(ParentTeacherConversation $conversation)
    {
        $conversation->update(['is_read' => true]);

        return response()->json([
            'status'  => 200,
            'message' => 'Conversation marked as read',
            'data'    => new ParentTeacherConversationResource($conversation),
        ]);
    }

    /**
     * Get unread conversations count
     */
    public function getUnreadCount(Request $request)
    {
        $userId   = $request->user_id;
        $userType = $request->user_type; // 'parent' or 'teacher'

        $query = ParentTeacherConversation::where('is_read', false);

        if ($userType === 'parent') {
            $query->where('parent_id', $userId);
        } else {
            $query->where('teacher_id', $userId);
        }

        $count = $query->count();

        return response()->json([
            'status' => 200,
            'data'   => [
                'unread_count' => $count,
            ],
        ]);
    }

    /**
     * Get recent conversations
     */
    public function getRecentConversations($userId, $userType, $limit = 5)
    {
        $query = ParentTeacherConversation::with(['parent', 'teacher', 'messages']);

        if ($userType === 'parent') {
            $query->where('parent_id', $userId);
        } else {
            $query->where('teacher_id', $userId);
        }

        $conversations = $query->orderBy('updated_at', 'desc')
            ->take($limit)
            ->get();

        return response()->json([
            'status' => 200,
            'data'   => ParentTeacherConversationResource::collection($conversations),
        ]);
    }

    /**
     * Archive conversation
     */
    public function archiveConversation(ParentTeacherConversation $conversation)
    {
        $conversation->update(['is_archived' => true]);

        return response()->json([
            'status'  => 200,
            'message' => 'Conversation archived successfully',
            'data'    => new ParentTeacherConversationResource($conversation),
        ]);
    }

    /**
     * Restore archived conversation
     */
    public function restoreConversation(ParentTeacherConversation $conversation)
    {
        $conversation->update(['is_archived' => false]);

        return response()->json([
            'status'  => 200,
            'message' => 'Conversation restored successfully',
            'data'    => new ParentTeacherConversationResource($conversation),
        ]);
    }
}
