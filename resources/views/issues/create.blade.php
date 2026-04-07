<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-slate-900">Create Issue</h2>
            <a href="{{ route('issues.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">Back to issues</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
            <div class="surface-card p-6 md:p-8">
                <form method="POST" action="{{ route('issues.store') }}" class="space-y-6">
                    @csrf
                    @include('issues.partials.form')

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('issues.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Cancel</a>
                        <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">Submit Issue</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
