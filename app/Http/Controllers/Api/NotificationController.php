<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of notifications.
     */
    public function index()
    {
        $notifications = Notification::with('user')->latest()->paginate(10);

        return response()->json([
            'status' => 200,
            'data' => NotificationResource::collection($notifications),
            'pagination' => [
                'current_page' => $notifications->currentPage(),
                'last_page' => $notifications->lastPage(),
                'total' => $notifications->total(),
            ]
        ]);
    }

    /**
     * Store a newly created notification.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);

        $notification = Notification::create([
            'user_id' => $request->user_id,
            'message' => $request->message,
        ]);

        return response()->json([
            'status' => 201,
            'data' => new NotificationResource($notification),
        ], 201);
    }

    /**
     * Display the specified notification.
     */
    public function show(Notification $notification)
    {
        return response()->json([
            'status' => 200,
            'data' => new NotificationResource($notification),
        ], 200);
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead(Notification $notification)
    {
        $notification->update(['is_read' => true]);

        return response()->json([
            'status' => 200,
            'message' => 'Notification marked as read.',
            'data' => new NotificationResource($notification),
        ], 200);
    }

    /**
     * Delete the specified notification.
     */
    public function destroy(Notification $notification)
    {
        $notification->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Notification deleted successfully.',
        ], 200);
    }
}
