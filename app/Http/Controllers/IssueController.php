<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IssueController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $issues = Issue::with(['user', 'assignedEngineer'])
            ->visibleTo($user)
            ->latest()
            ->paginate(10);

        $stats = [
            'open' => Issue::visibleTo(Issue::query(), $user)->where('status', 'open')->count(),
            'in_progress' => Issue::visibleTo(Issue::query(), $user)->where('status', 'in_progress')->count(),
            'resolved' => Issue::visibleTo(Issue::query(), $user)->where('status', 'resolved')->count(),
            'closed' => Issue::visibleTo(Issue::query(), $user)->where('status', 'closed')->count(),
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

        Issue::create([
            ...$validated,
            'status' => 'open',
            'user_id' => Auth::id(),
        ]);

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

        return redirect()
            ->route('issues.show', $issue)
            ->with('success', 'Issue updated successfully.');
    }

    public function destroy(Issue $issue)
    {
        $user = Auth::user();

        abort_unless($user->hasRole('admin', 'manager') || $issue->user_id === $user->id, 403);

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

        $issue->update([
            'status' => $validated['status'],
        ]);

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
