<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-slate-900">Edit Change Request</h2>
            <a href="{{ route('change-requests.show', $changeRequest) }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">Back to request</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-5xl sm:px-6 lg:px-8">
            <div class="surface-card p-6 md:p-8">
                <form method="POST" action="{{ route('change-requests.update', $changeRequest) }}" class="space-y-6">
                    @csrf
                    @method('PUT')
                    @include('change_requests.partials.form', ['changeRequest' => $changeRequest])

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('change-requests.show', $changeRequest) }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Cancel</a>
                        <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">Update Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
