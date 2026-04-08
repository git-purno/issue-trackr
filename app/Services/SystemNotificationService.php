<?php

namespace App\Services;

use App\Models\ChangeRequest;
use App\Models\User;
use App\Notifications\SystemAlertNotification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class SystemNotificationService
{
    public function notifyUsers(iterable $users, string $title, string $message, ?string $url = null, string $category = 'update', ?string $notificationKey = null): void
    {
        $targetType = null;
        $targetId = null;

        if ($url && preg_match('#/issues/(\d+)$#', $url, $matches)) {
            $targetType = 'issue';
            $targetId = (int) $matches[1];
        } elseif ($url && preg_match('#/change-requests/(\d+)$#', $url, $matches)) {
            $targetType = 'change_request';
            $targetId = (int) $matches[1];
        }

        foreach ($this->uniqueUsers($users) as $user) {
            if ($notificationKey !== null && $this->alreadySent($user, $notificationKey)) {
                continue;
            }

            $user->notify(new SystemAlertNotification(
                $title,
                $message,
                $url,
                $category,
                $notificationKey,
                $targetType,
                $targetId
            ));
        }
    }

    public function sendDeadlineNotifications(): void
    {
        $windowStart = now();
        $windowEnd = now()->addDay();

        $scheduledRequests = ChangeRequest::with(['user', 'analyst', 'manager', 'admin'])
            ->where('status', 'scheduled')
            ->whereBetween('scheduled_at', [$windowStart, $windowEnd])
            ->get();

        $engineers = User::where('role', 'engineer')->get();

        foreach ($scheduledRequests as $request) {
            $dateKey = Carbon::parse($request->scheduled_at)->format('Ymd');
            $notificationKey = "deadline-change-request-{$request->id}-{$dateKey}";

            $recipients = collect([
                $request->user,
                $request->analyst,
                $request->manager,
                $request->admin,
            ])->filter()->merge($engineers);

            $this->notifyUsers(
                $recipients,
                'Upcoming change deadline',
                "Change request \"{$request->title}\" is scheduled for {$request->scheduled_at->format('d M Y, h:i A')}.",
                route('change-requests.show', $request),
                'deadline',
                $notificationKey
            );
        }
    }

    private function uniqueUsers(iterable $users): Collection
    {
        return collect($users)
            ->filter(fn ($user) => $user instanceof User)
            ->unique('id')
            ->values();
    }

    private function alreadySent(User $user, string $notificationKey): bool
    {
        return $user->notifications()
            ->where('type', SystemAlertNotification::class)
            ->get()
            ->contains(fn ($notification) => data_get($notification->data, 'notification_key') === $notificationKey);
    }
}
