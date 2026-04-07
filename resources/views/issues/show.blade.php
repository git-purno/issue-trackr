<x-app-layout>
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

    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-slate-900">{{ $issue->title }}</h2>
                <p class="mt-1 text-sm text-slate-500">Issue submitted by {{ $issue->user->name }}</p>
            </div>
            <div class="flex flex-wrap gap-2">
                @if (auth()->user()->hasRole('admin', 'manager'))
                    <a href="{{ route('issues.assign.form', $issue) }}" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">Assign</a>
                @endif
                @if (auth()->user()->hasRole('admin', 'manager') || (auth()->user()->hasRole('engineer') && $issue->assigned_to === auth()->id()))
                    <a href="{{ route('issues.status.form', $issue) }}" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">Update Status</a>
                @endif
                @if (auth()->user()->hasRole('admin', 'manager') || auth()->id() === $issue->user_id)
                    <a href="{{ route('issues.edit', $issue) }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Edit</a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            <div class="grid gap-6 lg:grid-cols-3">
                <div class="surface-card p-6 lg:col-span-2">
                    <h3 class="text-lg font-semibold text-slate-900">Issue Details</h3>
                    <dl class="mt-6 grid gap-6 md:grid-cols-2">
                        <div>
                            <dt class="text-sm text-slate-500">Priority</dt>
                            <dd class="mt-2"><span class="rounded-full px-3 py-1 text-xs font-semibold {{ $priorityClasses[$issue->priority] }}">{{ ucfirst($issue->priority) }}</span></dd>
                        </div>
                        <div>
                            <dt class="text-sm text-slate-500">Status</dt>
                            <dd class="mt-2"><span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses[$issue->status] }}">{{ str_replace('_', ' ', ucfirst($issue->status)) }}</span></dd>
                        </div>
                        <div>
                            <dt class="text-sm text-slate-500">Assigned Engineer</dt>
                            <dd class="mt-2 text-slate-900">{{ $issue->assignedEngineer?->name ?? 'Not assigned' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-slate-500">Created</dt>
                            <dd class="mt-2 text-slate-900">{{ $issue->created_at->format('d M Y, h:i A') }}</dd>
                        </div>
                        <div class="md:col-span-2">
                            <dt class="text-sm text-slate-500">Description</dt>
                            <dd class="mt-2 whitespace-pre-line text-slate-700">{{ $issue->description }}</dd>
                        </div>
                    </dl>

                    @if (auth()->user()->hasRole('admin', 'manager') || auth()->id() === $issue->user_id)
                        <div class="mt-6 border-t border-slate-100 pt-6">
                            <form method="POST" action="{{ route('issues.destroy', $issue) }}" onsubmit="return confirm('Delete this issue?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-lg border border-red-200 px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50">Delete Issue</button>
                            </form>
                        </div>
                    @endif
                </div>

                <div class="surface-card p-6">
                    <h3 class="text-lg font-semibold text-slate-900">Discussion</h3>
                    <form method="POST" action="{{ route('issues.comments.store', $issue) }}" class="mt-4 space-y-4">
                        @csrf
                        <div>
                            <textarea name="comment" rows="4" class="w-full rounded-lg border border-slate-300 px-4 py-3" placeholder="Add a comment..." required>{{ old('comment') }}</textarea>
                            <x-input-error :messages="$errors->get('comment')" class="mt-2" />
                        </div>
                        <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">Post Comment</button>
                    </form>
                </div>
            </div>

            <div class="surface-card p-6">
                <h3 class="text-lg font-semibold text-slate-900">Activity Thread</h3>
                <div class="mt-6 space-y-4">
                    @forelse ($issue->comments as $comment)
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                            <div class="flex items-center justify-between gap-4">
                                <p class="font-medium text-slate-900">{{ $comment->user->name }}</p>
                                <p class="text-xs text-slate-500">{{ $comment->created_at->diffForHumans() }}</p>
                            </div>
                            <p class="mt-2 whitespace-pre-line text-sm text-slate-600">{{ $comment->comment }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">No comments yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
