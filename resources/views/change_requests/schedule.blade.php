<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-slate-900">Schedule Change Request</h2>
            <a href="{{ route('change-requests.show', $changeRequest) }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">Back to request</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
            <div class="surface-card p-6 md:p-8">
                <form method="POST" action="{{ route('change-requests.schedule', $changeRequest) }}" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label for="scheduled_at" class="mb-2 block text-sm font-medium text-slate-700">Scheduled Date and Time</label>
                        <input id="scheduled_at" name="scheduled_at" type="datetime-local" value="{{ old('scheduled_at', optional($changeRequest->scheduled_at)->format('Y-m-d\TH:i')) }}" class="w-full rounded-lg border border-slate-300 px-4 py-3" required>
                        <x-input-error :messages="$errors->get('scheduled_at')" class="mt-2" />
                    </div>

                    <div>
                        <label for="rollback_plan" class="mb-2 block text-sm font-medium text-slate-700">Rollback Plan</label>
                        <textarea id="rollback_plan" name="rollback_plan" rows="6" class="w-full rounded-lg border border-slate-300 px-4 py-3" required>{{ old('rollback_plan', $changeRequest->rollback_plan) }}</textarea>
                        <x-input-error :messages="$errors->get('rollback_plan')" class="mt-2" />
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('change-requests.show', $changeRequest) }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Cancel</a>
                        <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">Schedule Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
