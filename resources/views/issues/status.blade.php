<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-slate-900">Update Issue Status</h2>
            <a href="{{ route('issues.show', $issue) }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">Back to issue</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
            <div class="surface-card p-6 md:p-8">
                <form method="POST" action="{{ route('issues.status.update', $issue) }}" class="space-y-6">
                    @csrf
                    @method('PATCH')
                    <div>
                        <label for="status" class="mb-2 block text-sm font-medium text-slate-700">Status</label>
                        <select id="status" name="status" class="w-full rounded-lg border border-slate-300 px-4 py-3" required>
                            @foreach (['open', 'in_progress', 'resolved', 'closed'] as $status)
                                <option value="{{ $status }}" @selected(old('status', $issue->status) === $status)>{{ str_replace('_', ' ', ucfirst($status)) }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('issues.show', $issue) }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Cancel</a>
                        <button type="submit" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">Update Status</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
