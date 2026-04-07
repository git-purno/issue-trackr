@props(['issue' => null])

<div class="space-y-6">
    <div>
        <label for="title" class="mb-2 block text-sm font-medium text-slate-700">Title</label>
        <input id="title" name="title" type="text" value="{{ old('title', $issue?->title) }}" class="w-full rounded-lg border border-slate-300 px-4 py-3" required>
        <x-input-error :messages="$errors->get('title')" class="mt-2" />
    </div>

    <div>
        <label for="description" class="mb-2 block text-sm font-medium text-slate-700">Description</label>
        <textarea id="description" name="description" rows="6" class="w-full rounded-lg border border-slate-300 px-4 py-3" required>{{ old('description', $issue?->description) }}</textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>

    <div>
        <label for="priority" class="mb-2 block text-sm font-medium text-slate-700">Priority</label>
        <select id="priority" name="priority" class="w-full rounded-lg border border-slate-300 px-4 py-3" required>
            @foreach (['low', 'medium', 'high'] as $priority)
                <option value="{{ $priority }}" @selected(old('priority', $issue?->priority ?? 'medium') === $priority)>
                    {{ ucfirst($priority) }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('priority')" class="mt-2" />
    </div>
</div>
