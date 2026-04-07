<?php

namespace App\Http\Controllers;

use App\Models\ChangeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChangeRequestController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $requests = ChangeRequest::with(['user', 'analyst', 'manager', 'admin'])
            ->visibleTo($user)
            ->latest()
            ->paginate(10);

        $changeStatsQuery = ChangeRequest::query()->visibleTo($user);

        $stats = [
            'submitted' => (clone $changeStatsQuery)->where('status', 'submitted')->count(),
            'in_review' => (clone $changeStatsQuery)->whereIn('status', ['analyst_approved', 'manager_approved', 'admin_approved'])->count(),
            'scheduled' => (clone $changeStatsQuery)->where('status', 'scheduled')->count(),
            'completed' => (clone $changeStatsQuery)->where('status', 'completed')->count(),
        ];

        return view('change_requests.index', compact('requests', 'stats'));
    }

    public function create()
    {
        return view('change_requests.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'justification' => ['required', 'string'],
            'risk_analysis' => ['required', 'string'],
            'affected_systems' => ['required', 'string'],
            'impact_level' => ['required', 'in:low,medium,high'],
        ]);

        $changeRequest = ChangeRequest::create([
            ...$validated,
            'status' => 'submitted',
            'user_id' => Auth::id(),
        ]);

        return redirect()
            ->route('change-requests.show', $changeRequest)
            ->with('success', 'Change request submitted successfully.');
    }

    public function show(ChangeRequest $changeRequest)
    {
        $this->authorizeView($changeRequest);

        $changeRequest->load(['user', 'analyst', 'manager', 'admin']);

        return view('change_requests.show', compact('changeRequest'));
    }

    public function edit(ChangeRequest $changeRequest)
    {
        $this->authorizeManage($changeRequest);

        return view('change_requests.edit', compact('changeRequest'));
    }

    public function update(Request $request, ChangeRequest $changeRequest)
    {
        $this->authorizeManage($changeRequest);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'justification' => ['required', 'string'],
            'risk_analysis' => ['required', 'string'],
            'affected_systems' => ['required', 'string'],
            'impact_level' => ['required', 'in:low,medium,high'],
        ]);

        $changeRequest->update($validated);

        return redirect()
            ->route('change-requests.show', $changeRequest)
            ->with('success', 'Change request updated successfully.');
    }

    public function destroy(ChangeRequest $changeRequest)
    {
        $user = Auth::user();

        abort_unless(
            $user->hasRole('admin') ||
            ($changeRequest->user_id === $user->id && $changeRequest->status === 'submitted'),
            403
        );

        $changeRequest->delete();

        return redirect()
            ->route('change-requests.index')
            ->with('success', 'Change request deleted successfully.');
    }

    public function approve(ChangeRequest $changeRequest)
    {
        $user = Auth::user();

        if ($user->hasRole('analyst') && $changeRequest->status === 'submitted') {
            $changeRequest->update([
                'status' => 'analyst_approved',
                'analyst_id' => $user->id,
                'analyst_approved_at' => now(),
            ]);

            return back()->with('success', 'Change request approved by analyst.');
        }

        if ($user->hasRole('manager') && $changeRequest->status === 'analyst_approved') {
            $changeRequest->update([
                'status' => 'manager_approved',
                'manager_id' => $user->id,
                'manager_approved_at' => now(),
            ]);

            return back()->with('success', 'Change request approved by manager.');
        }

        if ($user->hasRole('admin') && $changeRequest->status === 'manager_approved') {
            $changeRequest->update([
                'status' => 'admin_approved',
                'admin_id' => $user->id,
                'admin_approved_at' => now(),
            ]);

            return back()->with('success', 'Change request approved by admin.');
        }

        return back()->with('error', 'This change request is not ready for your approval step.');
    }

    public function scheduleForm(ChangeRequest $changeRequest)
    {
        abort_unless(Auth::user()->hasRole('admin'), 403);
        abort_unless($changeRequest->status === 'admin_approved', 403);

        return view('change_requests.schedule', compact('changeRequest'));
    }

    public function schedule(Request $request, ChangeRequest $changeRequest)
    {
        abort_unless(Auth::user()->hasRole('admin'), 403);
        abort_unless($changeRequest->status === 'admin_approved', 403);

        $validated = $request->validate([
            'scheduled_at' => ['required', 'date'],
            'rollback_plan' => ['required', 'string'],
        ]);

        $changeRequest->update([
            'scheduled_at' => $validated['scheduled_at'],
            'rollback_plan' => $validated['rollback_plan'],
            'status' => 'scheduled',
        ]);

        return redirect()
            ->route('change-requests.show', $changeRequest)
            ->with('success', 'Change request scheduled successfully.');
    }

    public function verify(ChangeRequest $changeRequest)
    {
        abort_unless(Auth::user()->hasRole('engineer'), 403);
        abort_unless($changeRequest->status === 'scheduled', 403);

        $changeRequest->update([
            'verified' => true,
            'status' => 'completed',
        ]);

        return redirect()
            ->route('change-requests.show', $changeRequest)
            ->with('success', 'Change request marked as completed.');
    }

    private function authorizeView(ChangeRequest $changeRequest): void
    {
        $user = Auth::user();

        if ($user->hasRole('admin', 'manager', 'analyst')) {
            return;
        }

        if ($user->hasRole('engineer') && in_array($changeRequest->status, ['scheduled', 'completed'], true)) {
            return;
        }

        if ($changeRequest->user_id === $user->id) {
            return;
        }

        abort(403);
    }

    private function authorizeManage(ChangeRequest $changeRequest): void
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            return;
        }

        if ($changeRequest->user_id === $user->id && $changeRequest->status === 'submitted') {
            return;
        }

        abort(403);
    }
}
