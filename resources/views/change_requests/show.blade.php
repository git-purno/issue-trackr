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
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-slate-900">{{ $changeRequest->title }}</h2>
                <p class="mt-1 text-sm text-slate-500">Submitted by {{ $changeRequest->user->name }}</p>
            </div>
            <div class="flex flex-wrap gap-2">
                @if ((auth()->user()->role === 'analyst' && $changeRequest->status === 'submitted') || (auth()->user()->role === 'manager' && $changeRequest->status === 'analyst_approved') || (auth()->user()->role === 'admin' && $changeRequest->status === 'manager_approved'))
                    <form method="POST" action="{{ route('change-requests.approve', $changeRequest) }}">
                        @csrf
                        <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">Approve</button>
                    </form>
                @endif
                @if (auth()->user()->role === 'admin' && $changeRequest->status === 'admin_approved')
                    <a href="{{ route('change-requests.schedule.form', $changeRequest) }}" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">Schedule</a>
                @endif
                @if (auth()->user()->role === 'engineer' && $changeRequest->status === 'scheduled')
                    <form method="POST" action="{{ route('change-requests.verify', $changeRequest) }}">
                        @csrf
                        <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">Verify</button>
                    </form>
                @endif
                @if (auth()->user()->role === 'admin' || (auth()->id() === $changeRequest->user_id && $changeRequest->status === 'submitted'))
                    <a href="{{ route('change-requests.edit', $changeRequest) }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Edit</a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            <div class="grid gap-6 lg:grid-cols-3">
                <div class="surface-card p-6 lg:col-span-2">
                    <div class="flex items-center justify-between gap-4">
                        <h3 class="text-lg font-semibold text-slate-900">Request Details</h3>
                        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses[$changeRequest->status] }}">{{ str_replace('_', ' ', ucfirst($changeRequest->status)) }}</span>
                    </div>

                    <dl class="mt-6 grid gap-6 md:grid-cols-2">
                        <div>
                            <dt class="text-sm text-slate-500">Impact Level</dt>
                            <dd class="mt-2 capitalize text-slate-900">{{ $changeRequest->impact_level }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-slate-500">Scheduled At</dt>
                            <dd class="mt-2 text-slate-900">{{ $changeRequest->scheduled_at?->format('d M Y, h:i A') ?? 'Not scheduled' }}</dd>
                        </div>
                        <div class="md:col-span-2">
                            <dt class="text-sm text-slate-500">Description</dt>
                            <dd class="mt-2 whitespace-pre-line text-slate-700">{{ $changeRequest->description }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-slate-500">Justification</dt>
                            <dd class="mt-2 whitespace-pre-line text-slate-700">{{ $changeRequest->justification }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-slate-500">Risk Analysis</dt>
                            <dd class="mt-2 whitespace-pre-line text-slate-700">{{ $changeRequest->risk_analysis }}</dd>
                        </div>
                        <div class="md:col-span-2">
                            <dt class="text-sm text-slate-500">Affected Systems</dt>
                            <dd class="mt-2 whitespace-pre-line text-slate-700">{{ $changeRequest->affected_systems }}</dd>
                        </div>
                        <div class="md:col-span-2">
                            <dt class="text-sm text-slate-500">Rollback Plan</dt>
                            <dd class="mt-2 whitespace-pre-line text-slate-700">{{ $changeRequest->rollback_plan ?: 'Rollback plan not added yet.' }}</dd>
                        </div>
                    </dl>

                    @if (auth()->user()->role === 'admin' || (auth()->id() === $changeRequest->user_id && $changeRequest->status === 'submitted'))
                        <div class="mt-6 border-t border-slate-100 pt-6">
                            <form method="POST" action="{{ route('change-requests.destroy', $changeRequest) }}" onsubmit="return confirm('Delete this change request?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-lg border border-red-200 px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50">Delete Request</button>
                            </form>
                        </div>
                    @endif
                </div>

                <div class="space-y-6">
                    <div class="surface-card p-6">
                        <h3 class="text-lg font-semibold text-slate-900">Approval Workflow</h3>
                        <div class="mt-4 space-y-4 text-sm text-slate-600">
                            <div class="feature-item">
                                <p class="font-semibold text-slate-800 mb-1">Analyst</p>
                                <p>{{ $changeRequest->analyst?->name ?? 'Pending approval' }}</p>
                                <p class="mt-1 text-xs text-slate-500">{{ $changeRequest->analyst_approved_at?->format('d M Y, h:i A') ?? 'Not approved yet' }}</p>
                            </div>
                            <div class="feature-item">
                                <p class="font-semibold text-slate-800 mb-1">Manager</p>
                                <p>{{ $changeRequest->manager?->name ?? 'Pending approval' }}</p>
                                <p class="mt-1 text-xs text-slate-500">{{ $changeRequest->manager_approved_at?->format('d M Y, h:i A') ?? 'Not approved yet' }}</p>
                            </div>
                            <div class="feature-item">
                                <p class="font-semibold text-slate-800 mb-1">Admin</p>
                                <p>{{ $changeRequest->admin?->name ?? 'Pending approval' }}</p>
                                <p class="mt-1 text-xs text-slate-500">{{ $changeRequest->admin_approved_at?->format('d M Y, h:i A') ?? 'Not approved yet' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="surface-card p-6">
                        <h3 class="text-lg font-semibold text-slate-900">Verification</h3>
                        <div class="mt-4 feature-item">
                            <p class="font-semibold text-slate-800 mb-1">Implementation Check</p>
                            <p>{{ $changeRequest->verified ? 'Verified and completed.' : 'Verification pending.' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
