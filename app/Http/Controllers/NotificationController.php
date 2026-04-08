<?php

namespace App\Http\Controllers;

use App\Services\SystemNotificationService;
use App\Support\NotificationLinkResolver;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function __construct(
        private readonly SystemNotificationService $notificationService,
    ) {
    }

    public function index()
    {
        $this->notificationService->sendDeadlineNotifications();

        $notifications = Auth::user()
            ->notifications()
            ->latest()
            ->paginate(15);

        $notifications->getCollection()->transform(function ($notification) {
            $notification->resolved_url = NotificationLinkResolver::resolve($notification->data);

            return $notification;
        });

        return view('notifications.index', compact('notifications'));
    }

    public function markRead(string $notification)
    {
        $notificationModel = Auth::user()
            ->notifications()
            ->where('id', $notification)
            ->firstOrFail();

        $notificationModel->markAsRead();

        return back()->with('success', 'Notification marked as read.');
    }

    public function markAllRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return back()->with('success', 'All notifications marked as read.');
    }
}
