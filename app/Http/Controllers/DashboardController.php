<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\ChangeRequest;
use App\Models\Issue;
use App\Services\SystemNotificationService;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(
        private readonly SystemNotificationService $notificationService,
    ) {
    }

    public function index()
    {
        $user = Auth::user();
        $this->notificationService->sendDeadlineNotifications();

        $issueBaseQuery = Issue::with(['user', 'assignedEngineer'])->visibleTo($user);
        $changeBaseQuery = ChangeRequest::with(['user', 'analyst', 'manager', 'admin'])->visibleTo($user);

        $issueStats = [
            'open' => (clone $issueBaseQuery)->where('status', 'open')->count(),
            'in_progress' => (clone $issueBaseQuery)->where('status', 'in_progress')->count(),
            'resolved' => (clone $issueBaseQuery)->where('status', 'resolved')->count(),
            'closed' => (clone $issueBaseQuery)->where('status', 'closed')->count(),
        ];

        $changeStats = [
            'submitted' => (clone $changeBaseQuery)->where('status', 'submitted')->count(),
            'in_review' => (clone $changeBaseQuery)->whereIn('status', ['analyst_approved', 'manager_approved', 'admin_approved'])->count(),
            'scheduled' => (clone $changeBaseQuery)->where('status', 'scheduled')->count(),
            'completed' => (clone $changeBaseQuery)->where('status', 'completed')->count(),
        ];

        $recentIssues = (clone $issueBaseQuery)->latest()->take(5)->get();
        $recentChangeRequests = (clone $changeBaseQuery)->latest()->take(5)->get();
        $recentActivities = ActivityLog::with('user')
            ->latest()
            ->take(6)
            ->get();

        return view('dashboard', compact('issueStats', 'changeStats', 'recentIssues', 'recentChangeRequests', 'recentActivities'));
    }
}
