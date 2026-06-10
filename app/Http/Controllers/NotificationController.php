<?php

namespace App\Http\Controllers;

use App\Http\Middleware\EnsureAuthenticated;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware(EnsureAuthenticated::class);
    }

    public function recent()
    {
        if ($redirect = $this->ensureAuthenticated()) {
            return $redirect;
        }

        $notifications = Notification::latest()->take(10)->get()->map(function ($notification) {
            return [
                'id' => $notification->id,
                'type' => $notification->type,
                'message' => $notification->message,
                'created_at' => $notification->created_at->diffForHumans(),
            ];
        });

        return response()->json(['notifications' => $notifications]);
    }
}
