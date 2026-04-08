<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\User;
use App\Services\ActivityLogService;
use App\Services\SystemNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IssueController extends Controller
{
    public function __construct(
        private readonly ActivityLogService $activityLogService,
        private readonly SystemNotificationService $notificationService,
    ) {
    }

    public function index()
    {
        $user = Auth::user();

        $issues = Issue::with(['user', 'assignedEngineer'])
            ->visibleTo($user)
            ->latest()
            ->paginate(10);

        $issueStatsQuery = Issue::query()->visibleTo($user);

        $stats = [
            'open' => (clone $issueStatsQuery)->where('status', 'open')->count(),
            'in_progress' => (clone $issueStatsQuery)->where('status', 'in_progress')->count(),
            'resolved' => (clone $issueStatsQuery)->where('status', 'resolved')->count(),
            'closed' => (clone $issueStatsQuery)->where('status', 'closed')->count(),
        ];

        return view('issues.index', compact('issues', 'stats'));
    }

    public function create()
    {
        return view('issues.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'priority' => ['required', 'in:low,medium,high'],
        ]);

        $issue = Issue::create([
            ...$validated,
            'status' => 'open',
            'user_id' => Auth::id(),
        ]);

        $this->activityLogService->log(
            Auth::id(),
            $issue,
            'issue.created',
            "Issue \"{$issue->title}\" was created.",
            ['priority' => $issue->priority]
        );

        $this->notificationService->notifyUsers(
            User::whereIn('role', ['admin', 'manager'])->get(),
            'New issue submitted',
            "A new issue titled \"{$issue->title}\" has been submitted.",
            route('issues.show', $issue),
            'update'
        );

        return redirect()
            ->route('issues.index')
            ->with('success', 'Issue submitted successfully.');
    }

    public function show(Issue $issue)
    {
        $this->authorizeView($issue);

        $issue->load(['user', 'assignedEngineer', 'comments.user']);

        return view('issues.show', compact('issue'));
    }

    public function edit(Issue $issue)
    {
        $this->authorizeManage($issue);

        return view('issues.edit', compact('issue'));
    }

    public function update(Request $request, Issue $issue)
    {
        $this->authorizeManage($issue);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'priority' => ['required', 'in:low,medium,high'],
        ]);

        $issue->update($validated);

        $this->activityLogService->log(
            Auth::id(),
            $issue,
            'issue.updated',
            "Issue \"{$issue->title}\" details were updated.",
            ['priority' => $issue->priority]
        );

        return redirect()
            ->route('issues.show', $issue)
            ->with('success', 'Issue updated successfully.');
    }

    public function destroy(Issue $issue)
    {
        $user = Auth::user();

        abort_unless($user->hasRole('admin', 'manager') || $issue->user_id === $user->id, 403);

        $this->activityLogService->log(
            Auth::id(),
            $issue,
            'issue.deleted',
            "Issue \"{$issue->title}\" was deleted."
        );

        $issue->delete();

        return redirect()
            ->route('issues.index')
            ->with('success', 'Issue deleted successfully.');
    }

    public function assignForm(Issue $issue)
    {
        abort_unless(Auth::user()->hasRole('admin', 'manager'), 403);

        $engineers = User::where('role', 'engineer')->orderBy('name')->get();

        return view('issues.assign', compact('issue', 'engineers'));
    }

    public function assign(Request $request, Issue $issue)
    {
        abort_unless(Auth::user()->hasRole('admin', 'manager'), 403);

        $validated = $request->validate([
            'engineer_id' => ['required', 'exists:users,id'],
        ]);

        $engineer = User::where('id', $validated['engineer_id'])
            ->where('role', 'engineer')
            ->firstOrFail();

        $issue->update([
            'assigned_to' => $engineer->id,
            'status' => $issue->status === 'open' ? 'in_progress' : $issue->status,
        ]);

        $this->activityLogService->log(
            Auth::id(),
            $issue,
            'issue.assigned',
            "Issue \"{$issue->title}\" was assigned to {$engineer->name}.",
            ['assigned_to' => $engineer->id]
        );

        $this->notificationService->notifyUsers(
            [$engineer, $issue->user],
            'Issue assigned',
            "Issue \"{$issue->title}\" has been assigned to {$engineer->name}.",
            route('issues.show', $issue),
            'approval'
        );

        return redirect()
            ->route('issues.show', $issue)
            ->with('success', 'Issue assigned successfully.');
    }

    public function statusForm(Issue $issue)
    {
        $this->authorizeStatusChange($issue);

        return view('issues.status', compact('issue'));
    }

    public function updateStatus(Request $request, Issue $issue)
    {
        $this->authorizeStatusChange($issue);

        $validated = $request->validate([
            'status' => ['required', 'in:open,in_progress,resolved,closed'],
        ]);

        $previousStatus = $issue->status;

        $issue->update([
            'status' => $validated['status'],
        ]);

        $this->activityLogService->log(
            Auth::id(),
            $issue,
            'issue.status_updated',
            "Issue \"{$issue->title}\" status changed from {$previousStatus} to {$issue->status}.",
            ['from' => $previousStatus, 'to' => $issue->status]
        );

        $recipients = collect([
            $issue->user,
            $issue->assignedEngineer,
        ])->filter();

        $this->notificationService->notifyUsers(
            $recipients,
            'Issue status updated',
            "Issue \"{$issue->title}\" is now marked as " . str_replace('_', ' ', $issue->status) . '.',
            route('issues.show', $issue),
            'update'
        );

        return redirect()
            ->route('issues.show', $issue)
            ->with('success', 'Issue status updated successfully.');
    }

    private function authorizeView(Issue $issue): void
    {
        $user = Auth::user();

        if ($user->hasRole('admin', 'manager', 'analyst')) {
            return;
        }

        if ($issue->user_id === $user->id || $issue->assigned_to === $user->id) {
            return;
        }

        abort(403);
    }

    private function authorizeManage(Issue $issue): void
    {
        $user = Auth::user();

        if ($user->hasRole('admin', 'manager') || $issue->user_id === $user->id) {
            return;
        }

        abort(403);
    }

    private function authorizeStatusChange(Issue $issue): void
    {
        $user = Auth::user();

        if ($user->hasRole('admin', 'manager')) {
            return;
        }

        if ($user->hasRole('engineer') && $issue->assigned_to === $user->id) {
            return;
        }

        abort(403);
    }
}

