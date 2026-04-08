<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-slate-900">Reporting & Analytics</h2>
                <p class="mt-1 text-sm text-slate-500">SLA compliance, issue trends, activity history, and export support.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('reports.export', 'issues') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Export Issues</a>
                <a href="{{ route('reports.export', 'change_requests') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Export Changes</a>
                <a href="{{ route('reports.export', 'activity_logs') }}" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">Export Activity Logs</a>
            </div>
        </div>
    </x-slot>

    @php
        $maxTrendValue = max(1, $issueTrend->flatMap(fn ($day) => [$day['created'], $day['resolved']])->max());
        $maxChangeValue = max(1, max($changeStatusBreakdown));
    @endphp

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            <div class="grid gap-6 md:grid-cols-3">
                <div class="surface-card p-6">
                    <p class="text-sm text-slate-500">SLA Compliant Issues</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ $slaCompliant }}</p>
                    <p class="mt-2 text-sm text-slate-600">Out of {{ $slaTotal }} resolved or closed issues.</p>
                </div>
                <div class="surface-card p-6 md:col-span-2">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-sm text-slate-500">SLA Compliance Rate</p>
                            <p class="mt-2 text-3xl font-bold text-slate-900">{{ $slaPercent }}%</p>
                        </div>
                        <div class="h-3 w-2/3 rounded-full bg-slate-100">
                            <div class="h-3 rounded-full bg-emerald-500" style="width: {{ $slaPercent }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="surface-card p-6">
                    <h3 class="text-lg font-semibold text-slate-900">Issue Trends</h3>
                    <p class="mt-1 text-sm text-slate-500">Created vs resolved issues over the last 7 days.</p>
                    <div class="mt-6 space-y-4">
                        @foreach ($issueTrend as $point)
                            <div>
                                <div class="mb-2 flex items-center justify-between text-sm">
                                    <span class="font-medium text-slate-700">{{ $point['label'] }}</span>
                                    <span class="text-slate-500">Created {{ $point['created'] }} / Resolved {{ $point['resolved'] }}</span>
                                </div>
                                <div class="space-y-2">
                                    <div class="h-3 rounded-full bg-slate-100">
                                        <div class="h-3 rounded-full bg-blue-500" style="width: {{ ($point['created'] / $maxTrendValue) * 100 }}%"></div>
                                    </div>
                                    <div class="h-3 rounded-full bg-slate-100">
                                        <div class="h-3 rounded-full bg-emerald-500" style="width: {{ ($point['resolved'] / $maxTrendValue) * 100 }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="surface-card p-6">
                    <h3 class="text-lg font-semibold text-slate-900">Change Request Status</h3>
                    <p class="mt-1 text-sm text-slate-500">Current pipeline distribution for change requests.</p>
                    <div class="mt-6 space-y-4">
                        @foreach ($changeStatusBreakdown as $label => $value)
                            <div>
                                <div class="mb-2 flex items-center justify-between text-sm">
                                    <span class="font-medium capitalize text-slate-700">{{ str_replace('_', ' ', $label) }}</span>
                                    <span class="text-slate-500">{{ $value }}</span>
                                </div>
                                <div class="h-3 rounded-full bg-slate-100">
                                    <div class="h-3 rounded-full bg-slate-900" style="width: {{ ($value / $maxChangeValue) * 100 }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="surface-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Activity Logs</h3>
                        <p class="mt-1 text-sm text-slate-500">Recent operational activity across issues and change requests.</p>
                    </div>
                    <a href="{{ route('reports.export', 'activity_logs') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Export CSV</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="border-b border-slate-200 text-left text-slate-500">
                            <tr>
                                <th class="pb-3 pr-4">Event</th>
                                <th class="pb-3 pr-4">Description</th>
                                <th class="pb-3 pr-4">User</th>
                                <th class="pb-3">Time</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($activityLogs as $log)
                                <tr>
                                    <td class="py-3 pr-4 font-medium text-slate-800">{{ str_replace('.', ' / ', $log->event) }}</td>
                                    <td class="py-3 pr-4 text-slate-600">{{ $log->description }}</td>
                                    <td class="py-3 pr-4 text-slate-600">{{ $log->user?->name ?? 'System' }}</td>
                                    <td class="py-3 text-slate-500">{{ $log->created_at->format('d M Y, h:i A') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-6 text-center text-slate-500">No activity logs available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $activityLogs->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
