<?php

namespace App\Http\Controllers\Apprenant;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    public function markAsRead(Request $request, $id)
    {
      
        $notification = auth()->user()->notifications()->find($id);
     

        $notification->update(['read' => true]);
        return response()->json(['success' => true]);
    }
}
