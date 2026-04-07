<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-slate-900">Issue Management</h2>
            <a href="{{ route('issues.create') }}" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">Create Issue</a>
        </div>
    </x-slot>

    @php
        $statusClasses = [
            'open' => 'bg-amber-100 text-amber-800',
            'in_progress' => 'bg-blue-100 text-blue-800',
            'resolved' => 'bg-emerald-100 text-emerald-800',
            'closed' => 'bg-slate-200 text-slate-700',
        ];
        $priorityClasses = [
            'low' => 'bg-slate-100 text-slate-700',
            'medium' => 'bg-yellow-100 text-yellow-800',
            'high' => 'bg-red-100 text-red-800',
        ];
    @endphp

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <div class="surface-card p-5"><p class="text-sm text-slate-500">Open</p><p class="mt-2 text-3xl font-bold text-slate-900">{{ $stats['open'] }}</p></div>
                <div class="surface-card p-5"><p class="text-sm text-slate-500">In Progress</p><p class="mt-2 text-3xl font-bold text-slate-900">{{ $stats['in_progress'] }}</p></div>
                <div class="surface-card p-5"><p class="text-sm text-slate-500">Resolved</p><p class="mt-2 text-3xl font-bold text-slate-900">{{ $stats['resolved'] }}</p></div>
                <div class="surface-card p-5"><p class="text-sm text-slate-500">Closed</p><p class="mt-2 text-3xl font-bold text-slate-900">{{ $stats['closed'] }}</p></div>
            </div>

            <div class="surface-card overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50 text-left text-slate-500">
                            <tr>
                                <th class="px-6 py-4 font-medium">Title</th>
                                <th class="px-6 py-4 font-medium">Reporter</th>
                                <th class="px-6 py-4 font-medium">Assigned Engineer</th>
                                <th class="px-6 py-4 font-medium">Priority</th>
                                <th class="px-6 py-4 font-medium">Status</th>
                                <th class="px-6 py-4 font-medium">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($issues as $issue)
                                <tr>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('issues.show', $issue) }}" class="font-medium text-slate-900 hover:text-blue-600">{{ $issue->title }}</a>
                                    </td>
                                    <td class="px-6 py-4 text-slate-600">{{ $issue->user->name }}</td>
                                    <td class="px-6 py-4 text-slate-600">{{ $issue->assignedEngineer?->name ?? 'Unassigned' }}</td>
                                    <td class="px-6 py-4"><span class="rounded-full px-3 py-1 text-xs font-semibold {{ $priorityClasses[$issue->priority] }}">{{ ucfirst($issue->priority) }}</span></td>
                                    <td class="px-6 py-4"><span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses[$issue->status] }}">{{ str_replace('_', ' ', ucfirst($issue->status)) }}</span></td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap gap-2">
                                            <a href="{{ route('issues.show', $issue) }}" class="rounded-md border border-slate-200 px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50">View</a>
                                            @if (auth()->user()->hasRole('admin', 'manager'))
                                                <a href="{{ route('issues.assign.form', $issue) }}" class="rounded-md bg-blue-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-blue-700">Assign</a>
                                            @endif
                                            @if (auth()->user()->hasRole('admin', 'manager') || (auth()->user()->hasRole('engineer') && $issue->assigned_to === auth()->id()))
                                                <a href="{{ route('issues.status.form', $issue) }}" class="rounded-md bg-slate-900 px-3 py-1.5 text-xs font-medium text-white hover:bg-slate-800">Update Status</a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-slate-500">No issues found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="border-t border-slate-100 px-6 py-4">
                    {{ $issues->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
