<?php

namespace App\Http\Controllers\Apprenant;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    public function markAsRead(Request $request, $id)
    {
        Log::info('Attempting to mark notification as read', [
            'user_id' => auth()->user()->id,
            'notification_id' => $id,
        ]);

        $notification = auth()->user()->notifications()->find($id);
        if (!$notification) {
            Log::warning('Notification not found or does not belong to user', ['notification_id' => $id]);
            return response()->json(['success' => false, 'message' => 'Notification not found'], 404);
        }

        $notification->update(['read' => true]);
        Log::info('Notification marked as read', ['notification_id' => $id]);
        return response()->json(['success' => true]);
    }
}
