<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\ChangeRequest;
use App\Models\Issue;
use Illuminate\Support\Facades\Response;

class ReportController extends Controller
{
    public function index()
    {
        $resolvedIssues = Issue::whereIn('status', ['resolved', 'closed'])->get();

        $slaCompliant = $resolvedIssues->filter(function (Issue $issue) {
            $hours = $issue->created_at->diffInHours($issue->updated_at);
            $targetHours = match ($issue->priority) {
                'high' => 24,
                'medium' => 72,
                default => 120,
            };

            return $hours <= $targetHours;
        })->count();

        $slaTotal = $resolvedIssues->count();
        $slaPercent = $slaTotal > 0 ? (int) round(($slaCompliant / $slaTotal) * 100) : 0;

        $issueTrend = collect(range(6, 0))
            ->reverse()
            ->map(function (int $daysAgo) {
                $date = now()->subDays($daysAgo)->startOfDay();

                return [
                    'label' => $date->format('d M'),
                    'created' => Issue::whereDate('created_at', $date)->count(),
                    'resolved' => Issue::whereDate('updated_at', $date)->whereIn('status', ['resolved', 'closed'])->count(),
                ];
            });

        $changeStatusBreakdown = [
            'submitted' => ChangeRequest::where('status', 'submitted')->count(),
            'in_review' => ChangeRequest::whereIn('status', ['analyst_approved', 'manager_approved', 'admin_approved'])->count(),
            'scheduled' => ChangeRequest::where('status', 'scheduled')->count(),
            'completed' => ChangeRequest::where('status', 'completed')->count(),
        ];

        $activityLogs = ActivityLog::with('user')->latest()->paginate(15);

        return view('reports.index', compact('slaCompliant', 'slaTotal', 'slaPercent', 'issueTrend', 'changeStatusBreakdown', 'activityLogs'));
    }

    public function export(string $type)
    {
        return match ($type) {
            'issues' => $this->exportIssues(),
            'change_requests' => $this->exportChangeRequests(),
            'activity_logs' => $this->exportActivityLogs(),
            default => abort(404),
        };
    }

    private function exportIssues()
    {
        $rows = Issue::with(['user', 'assignedEngineer'])->latest()->get()->map(function (Issue $issue) {
            return [
                'ID' => $issue->id,
                'Title' => $issue->title,
                'Priority' => $issue->priority,
                'Status' => $issue->status,
                'Reporter' => $issue->user?->name,
                'Assigned Engineer' => $issue->assignedEngineer?->name,
                'Created At' => $issue->created_at,
                'Updated At' => $issue->updated_at,
            ];
        });

        return $this->streamCsv('issues-report.csv', $rows);
    }

    private function exportChangeRequests()
    {
        $rows = ChangeRequest::with(['user', 'analyst', 'manager', 'admin'])->latest()->get()->map(function (ChangeRequest $request) {
            return [
                'ID' => $request->id,
                'Title' => $request->title,
                'Impact Level' => $request->impact_level,
                'Status' => $request->status,
                'Requester' => $request->user?->name,
                'Analyst' => $request->analyst?->name,
                'Manager' => $request->manager?->name,
                'Admin' => $request->admin?->name,
                'Scheduled At' => $request->scheduled_at,
                'Verified' => $request->verified ? 'Yes' : 'No',
            ];
        });

        return $this->streamCsv('change-requests-report.csv', $rows);
    }

    private function exportActivityLogs()
    {
        $rows = ActivityLog::with('user')->latest()->get()->map(function (ActivityLog $log) {
            return [
                'ID' => $log->id,
                'Event' => $log->event,
                'Description' => $log->description,
                'User' => $log->user?->name,
                'Subject Type' => $log->subject_type,
                'Subject ID' => $log->subject_id,
                'Created At' => $log->created_at,
            ];
        });

        return $this->streamCsv('activity-logs-report.csv', $rows);
    }

    private function streamCsv(string $filename, $rows)
    {
        return Response::streamDownload(function () use ($rows) {
            $handle = fopen('php://output', 'w');

            if ($rows->isNotEmpty()) {
                fputcsv($handle, array_keys($rows->first()));

                foreach ($rows as $row) {
                    fputcsv($handle, $row);
                }
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
