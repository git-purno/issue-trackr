<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-slate-900">Smart Notifications</h2>
                <p class="mt-1 text-sm text-slate-500">Updates, deadlines, and approval alerts for your workflow.</p>
            </div>
            @if (auth()->user()->unreadNotifications->count())
                <form method="POST" action="{{ route('notifications.read-all') }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">Mark all as read</button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-5xl space-y-4 sm:px-6 lg:px-8">
            @forelse ($notifications as $notification)
                <div class="surface-card p-6">
                    <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3">
                                <span class="soft-badge">{{ ucfirst(data_get($notification->data, 'category', 'update')) }}</span>
                                @if (is_null($notification->read_at))
                                    <span class="rounded-full bg-blue-100 px-2 py-1 text-xs font-semibold text-blue-700">Unread</span>
                                @endif
                            </div>
                            <h3 class="mt-4 text-lg font-semibold text-slate-900">{{ data_get($notification->data, 'title') }}</h3>
                            <p class="mt-2 text-sm leading-6 text-slate-600">{{ data_get($notification->data, 'message') }}</p>
                            <p class="mt-3 text-xs text-slate-500">{{ $notification->created_at->format('d M Y, h:i A') }}</p>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @if ($notification->resolved_url)
                                <a href="{{ $notification->resolved_url }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Open</a>
                            @else
                                <span class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-400">Unavailable</span>
                            @endif
                            @if (is_null($notification->read_at))
                                <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">Mark read</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="surface-card p-8 text-center text-slate-500">
                    No notifications available.
                </div>
            @endforelse

            <div>
                {{ $notifications->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
