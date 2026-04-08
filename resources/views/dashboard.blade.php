<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
                <div class="surface-card p-6">
                    <p class="text-sm text-slate-500 mb-2">Open Issues</p>
                    <p class="text-3xl font-bold text-slate-900">{{ $issueStats['open'] }}</p>
                </div>
                <div class="surface-card p-6">
                    <p class="text-sm text-slate-500 mb-2">In Progress Issues</p>
                    <p class="text-3xl font-bold text-slate-900">{{ $issueStats['in_progress'] }}</p>
                </div>
                <div class="surface-card p-6">
                    <p class="text-sm text-slate-500 mb-2">Scheduled Changes</p>
                    <p class="text-3xl font-bold text-slate-900">{{ $changeStats['scheduled'] }}</p>
                </div>
                <div class="surface-card p-6">
                    <p class="text-sm text-slate-500 mb-2">Completed Changes</p>
                    <p class="text-3xl font-bold text-slate-900">{{ $changeStats['completed'] }}</p>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="surface-card p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-slate-900">Issue Overview</h3>
                        <a href="{{ route('issues.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">View all</a>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="feature-item">
                            <p class="font-semibold text-slate-800 mb-1">Open</p>
                            <p>{{ $issueStats['open'] }} items awaiting action.</p>
                        </div>
                        <div class="feature-item">
                            <p class="font-semibold text-slate-800 mb-1">Resolved</p>
                            <p>{{ $issueStats['resolved'] }} issues completed and ready to close.</p>
                        </div>
                        <div class="feature-item">
                            <p class="font-semibold text-slate-800 mb-1">In Progress</p>
                            <p>{{ $issueStats['in_progress'] }} issues currently assigned.</p>
                        </div>
                        <div class="feature-item">
                            <p class="font-semibold text-slate-800 mb-1">Closed</p>
                            <p>{{ $issueStats['closed'] }} issues fully completed.</p>
                        </div>
                    </div>
                </div>

                <div class="surface-card p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-slate-900">Change Request Overview</h3>
                        <a href="{{ route('change-requests.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">View all</a>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="feature-item">
                            <p class="font-semibold text-slate-800 mb-1">Submitted</p>
                            <p>{{ $changeStats['submitted'] }} requests waiting to enter review.</p>
                        </div>
                        <div class="feature-item">
                            <p class="font-semibold text-slate-800 mb-1">In Review</p>
                            <p>{{ $changeStats['in_review'] }} requests moving through approvals.</p>
                        </div>
                        <div class="feature-item">
                            <p class="font-semibold text-slate-800 mb-1">Scheduled</p>
                            <p>{{ $changeStats['scheduled'] }} approved changes planned for execution.</p>
                        </div>
                        <div class="feature-item">
                            <p class="font-semibold text-slate-800 mb-1">Completed</p>
                            <p>{{ $changeStats['completed'] }} verified changes completed.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="surface-card p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-slate-900">Recent Issues</h3>
                        <a href="{{ route('issues.create') }}" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">Create Issue</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="border-b border-slate-200 text-left text-slate-500">
                                    <th class="pb-3 pr-4">Title</th>
                                    <th class="pb-3 pr-4">Priority</th>
                                    <th class="pb-3">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentIssues as $issue)
                                    <tr class="border-b border-slate-100">
                                        <td class="py-3 pr-4"><a href="{{ route('issues.show', $issue) }}" class="font-medium text-slate-800 hover:text-blue-600">{{ $issue->title }}</a></td>
                                        <td class="py-3 pr-4 capitalize">{{ str_replace('_', ' ', $issue->priority) }}</td>
                                        <td class="py-3 capitalize">{{ str_replace('_', ' ', $issue->status) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="py-4 text-slate-500">No issues available yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="surface-card p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-slate-900">Recent Change Requests</h3>
                        <a href="{{ route('change-requests.create') }}" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">New Request</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="border-b border-slate-200 text-left text-slate-500">
                                    <th class="pb-3 pr-4">Title</th>
                                    <th class="pb-3 pr-4">Impact</th>
                                    <th class="pb-3">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentChangeRequests as $request)
                                    <tr class="border-b border-slate-100">
                                        <td class="py-3 pr-4"><a href="{{ route('change-requests.show', $request) }}" class="font-medium text-slate-800 hover:text-blue-600">{{ $request->title }}</a></td>
                                        <td class="py-3 pr-4 capitalize">{{ $request->impact_level }}</td>
                                        <td class="py-3 capitalize">{{ str_replace('_', ' ', $request->status) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="py-4 text-slate-500">No change requests available yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="surface-card p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-slate-900">Unread Notifications</h3>
                        <a href="{{ route('notifications.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">Open notifications</a>
                    </div>
                    <div class="space-y-3">
                        @forelse (auth()->user()->unreadNotifications->take(5) as $notification)
                            <div class="feature-item">
                                <div class="flex items-center justify-between gap-4">
                                    <p class="font-semibold text-slate-800">{{ data_get($notification->data, 'title') }}</p>
                                    <span class="soft-badge">{{ ucfirst(data_get($notification->data, 'category', 'update')) }}</span>
                                </div>
                                <p class="mt-2 text-sm text-slate-600">{{ data_get($notification->data, 'message') }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">No unread notifications.</p>
                        @endforelse
                    </div>
                </div>

                <div class="surface-card p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-slate-900">Recent Activity</h3>
                        @if (auth()->user()->hasRole('admin', 'manager', 'analyst'))
                            <a href="{{ route('reports.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">Open reports</a>
                        @endif
                    </div>
                    <div class="space-y-3">
                        @forelse ($recentActivities as $activity)
                            <div class="feature-item">
                                <div class="flex items-center justify-between gap-4">
                                    <p class="font-semibold text-slate-800">{{ $activity->description }}</p>
                                    <p class="text-xs text-slate-500">{{ $activity->created_at->diffForHumans() }}</p>
                                </div>
                                <p class="mt-1 text-xs text-slate-500">{{ $activity->user?->name ?? 'System' }} • {{ str_replace('.', ' / ', $activity->event) }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">No activity recorded yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
