<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AIChatLogResource;
use App\Models\AIChatLog;
use App\Services\OpenRouterService;
use Illuminate\Http\Request;
use App\Events\AiChatMessageSent;

class AIChatLogController extends Controller
{
    protected $openRouterService;

    public function __construct(OpenRouterService $openRouterService)
    {
        $this->openRouterService = $openRouterService;
    }

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
     * Store a newly created chat log and get AI response.
     */
    public function store(Request $request)
    {
        // Validate and store the user chat message
        $validated = $request->validate([
            'message' => 'required|string',
            'user_id' => 'required|exists:users,id',
        ]);

        // Store user message
        $userMessage = AIChatLog::create([
            'user_id' => $validated['user_id'],
            'message' => $validated['message'],
            'is_ai' => false,
        ]);

        // Broadcast the user message
        broadcast(new AiChatMessageSent($userMessage));

        // Get conversation history for context
        $conversationHistory = $this->getConversationHistory($validated['user_id']);

        // Get AI response from OpenRouter/DeepSeek
        $aiResponseText = $this->openRouterService->sendMessage($validated['message'], $conversationHistory);

        // Store AI response
        $aiResponse = AIChatLog::create([
            'user_id' => $validated['user_id'],
            'message' => $aiResponseText,
            'is_ai' => true,
        ]);

        // Broadcast the AI response
        broadcast(new AiChatMessageSent($aiResponse));

        return response()->json([
            'status' => 200,
            'message' => 'Message sent successfully.',
            'data' => [
                'user_message' => new AIChatLogResource($userMessage),
                'ai_response' => new AIChatLogResource($aiResponse),
            ],
        ]);
    }

    /**
     * Get conversation history for AI context
     */
    private function getConversationHistory($userId)
    {
        // Get recent messages for context (limit to last 10 for example)
        $recentMessages = AIChatLog::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->reverse();

        $history = [];
        foreach ($recentMessages as $message) {
            $history[] = [
                'role' => $message->is_ai ? 'assistant' : 'user',
                'content' => $message->message
            ];
        }

        return $history;
    }

    /**
     * Display chat history for a specific user
     */
    public function userHistory($userId)
    {
        $chatLogs = AIChatLog::where('user_id', $userId)
            ->orderBy('created_at', 'asc')
            ->paginate(20);

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