<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Issue;
use App\Services\ActivityLogService;
use App\Services\SystemNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function __construct(
        private readonly ActivityLogService $activityLogService,
        private readonly SystemNotificationService $notificationService,
    ) {
    }

    public function store(Request $request, Issue $issue)
    {
        $user = Auth::user();

        abort_unless(
            $user->hasRole('admin', 'manager', 'analyst') ||
            $issue->user_id === $user->id ||
            $issue->assigned_to === $user->id,
            403
        );

        $validated = $request->validate([
            'comment' => ['required', 'string'],
        ]);

        Comment::create([
            'issue_id' => $issue->id,
            'user_id' => $user->id,
            'comment' => $validated['comment'],
        ]);

        $this->activityLogService->log(
            $user->id,
            $issue,
            'issue.comment_added',
            "{$user->name} added a comment to issue \"{$issue->title}\"."
        );

        $this->notificationService->notifyUsers(
            collect([$issue->user, $issue->assignedEngineer])->filter()->reject(fn ($recipient) => $recipient->id === $user->id),
            'New issue comment',
            "{$user->name} added a new comment on issue \"{$issue->title}\".",
            route('issues.show', $issue),
            'update'
        );

        return redirect()
            ->route('issues.show', $issue)
            ->with('success', 'Comment added successfully.');
    }
}
