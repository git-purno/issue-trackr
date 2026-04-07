<x-app-layout>
    @php
        $statusClasses = [
            'submitted' => 'bg-amber-100 text-amber-800',
            'analyst_approved' => 'bg-blue-100 text-blue-800',
            'manager_approved' => 'bg-indigo-100 text-indigo-800',
            'admin_approved' => 'bg-violet-100 text-violet-800',
            'scheduled' => 'bg-cyan-100 text-cyan-800',
            'completed' => 'bg-emerald-100 text-emerald-800',
        ];
    @endphp

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-slate-900">Change Request Tracking</h2>
            <a href="{{ route('change-requests.create') }}" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">New Request</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <div class="surface-card p-5"><p class="text-sm text-slate-500">Submitted</p><p class="mt-2 text-3xl font-bold text-slate-900">{{ $stats['submitted'] }}</p></div>
                <div class="surface-card p-5"><p class="text-sm text-slate-500">In Review</p><p class="mt-2 text-3xl font-bold text-slate-900">{{ $stats['in_review'] }}</p></div>
                <div class="surface-card p-5"><p class="text-sm text-slate-500">Scheduled</p><p class="mt-2 text-3xl font-bold text-slate-900">{{ $stats['scheduled'] }}</p></div>
                <div class="surface-card p-5"><p class="text-sm text-slate-500">Completed</p><p class="mt-2 text-3xl font-bold text-slate-900">{{ $stats['completed'] }}</p></div>
            </div>

            <div class="surface-card overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50 text-left text-slate-500">
                            <tr>
                                <th class="px-6 py-4 font-medium">Title</th>
                                <th class="px-6 py-4 font-medium">Requester</th>
                                <th class="px-6 py-4 font-medium">Impact</th>
                                <th class="px-6 py-4 font-medium">Stage</th>
                                <th class="px-6 py-4 font-medium">Status</th>
                                <th class="px-6 py-4 font-medium">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($requests as $request)
                                <tr>
                                    <td class="px-6 py-4"><a href="{{ route('change-requests.show', $request) }}" class="font-medium text-slate-900 hover:text-blue-600">{{ $request->title }}</a></td>
                                    <td class="px-6 py-4 text-slate-600">{{ $request->user->name }}</td>
                                    <td class="px-6 py-4 capitalize">{{ $request->impact_level }}</td>
                                    <td class="px-6 py-4 text-slate-600">
                                        @if ($request->admin_approved_at)
                                            Fully approved
                                        @elseif ($request->manager_approved_at)
                                            Awaiting admin
                                        @elseif ($request->analyst_approved_at)
                                            Awaiting manager
                                        @else
                                            Awaiting analyst
                                        @endif
                                    </td>
                                    <td class="px-6 py-4"><span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses[$request->status] }}">{{ str_replace('_', ' ', ucfirst($request->status)) }}</span></td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap gap-2">
                                            <a href="{{ route('change-requests.show', $request) }}" class="rounded-md border border-slate-200 px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50">View</a>
                                            @if ((auth()->user()->role === 'analyst' && $request->status === 'submitted') || (auth()->user()->role === 'manager' && $request->status === 'analyst_approved') || (auth()->user()->role === 'admin' && $request->status === 'manager_approved'))
                                                <form method="POST" action="{{ route('change-requests.approve', $request) }}">
                                                    @csrf
                                                    <button type="submit" class="rounded-md bg-blue-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-blue-700">Approve</button>
                                                </form>
                                            @endif
                                            @if (auth()->user()->role === 'admin' && $request->status === 'admin_approved')
                                                <a href="{{ route('change-requests.schedule.form', $request) }}" class="rounded-md bg-slate-900 px-3 py-1.5 text-xs font-medium text-white hover:bg-slate-800">Schedule</a>
                                            @endif
                                            @if (auth()->user()->role === 'engineer' && $request->status === 'scheduled')
                                                <form method="POST" action="{{ route('change-requests.verify', $request) }}">
                                                    @csrf
                                                    <button type="submit" class="rounded-md bg-emerald-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-emerald-700">Verify</button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-slate-500">No change requests found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="border-t border-slate-100 px-6 py-4">
                    {{ $requests->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
