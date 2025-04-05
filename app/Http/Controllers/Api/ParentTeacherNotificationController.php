<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ParentTeacherNotificationResource;
use App\Models\ParentTeacherNotification;
use Illuminate\Http\Request;

class ParentTeacherNotificationController extends Controller
{
    /**
     * Get all notifications for a specific recipient.
     */
    public function index($recipientId)
    {
        $notifications = ParentTeacherNotification::where('recipient_id', $recipientId)
            ->with('recipient', 'conversation')
            ->paginate(10);

        return response()->json([
            'status' => 200,
            'data' => ParentTeacherNotificationResource::collection($notifications),
            'pagination' => [
                'current_page' => $notifications->currentPage(),
                'last_page' => $notifications->lastPage(),
                'total' => $notifications->total(),
            ]
        ]);
    }

    /**
     * Store a new notification for a recipient.
     */
    public function store(Request $request)
    {
        $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'conversation_id' => 'required|exists:parent_teacher_conversations,id',
            'message_preview' => 'required|string',
        ]);

        $notification = ParentTeacherNotification::create($request->all());

        return response()->json([
            'status' => 201,
            'data' => new ParentTeacherNotificationResource($notification),
        ], 201);
    }

    /**
     * Show a single notification.
     */
    public function show(ParentTeacherNotification $notification)
    {
        return response()->json([
            'status' => 200,
            'data' => new ParentTeacherNotificationResource($notification),
        ], 200);
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead(ParentTeacherNotification $notification)
    {
        $notification->update(['is_read' => true]);

        return response()->json([
            'status' => 200,
            'message' => 'Notification marked as read.',
        ], 200);
    }

    /**
     * Delete a notification.
     */
    public function destroy(ParentTeacherNotification $notification)
    {
        $notification->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Notification deleted successfully.',
        ], 200);
    }
}
