@props(['changeRequest' => null])

<div class="grid gap-6 md:grid-cols-2">
    <div class="md:col-span-2">
        <label for="title" class="mb-2 block text-sm font-medium text-slate-700">Title</label>
        <input id="title" name="title" type="text" value="{{ old('title', $changeRequest?->title) }}" class="w-full rounded-lg border border-slate-300 px-4 py-3" required>
        <x-input-error :messages="$errors->get('title')" class="mt-2" />
    </div>

    <div class="md:col-span-2">
        <label for="description" class="mb-2 block text-sm font-medium text-slate-700">Description</label>
        <textarea id="description" name="description" rows="5" class="w-full rounded-lg border border-slate-300 px-4 py-3" required>{{ old('description', $changeRequest?->description) }}</textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>

    <div>
        <label for="justification" class="mb-2 block text-sm font-medium text-slate-700">Justification</label>
        <textarea id="justification" name="justification" rows="4" class="w-full rounded-lg border border-slate-300 px-4 py-3" required>{{ old('justification', $changeRequest?->justification) }}</textarea>
        <x-input-error :messages="$errors->get('justification')" class="mt-2" />
    </div>

    <div>
        <label for="risk_analysis" class="mb-2 block text-sm font-medium text-slate-700">Risk Analysis</label>
        <textarea id="risk_analysis" name="risk_analysis" rows="4" class="w-full rounded-lg border border-slate-300 px-4 py-3" required>{{ old('risk_analysis', $changeRequest?->risk_analysis) }}</textarea>
        <x-input-error :messages="$errors->get('risk_analysis')" class="mt-2" />
    </div>

    <div>
        <label for="affected_systems" class="mb-2 block text-sm font-medium text-slate-700">Affected Systems</label>
        <textarea id="affected_systems" name="affected_systems" rows="4" class="w-full rounded-lg border border-slate-300 px-4 py-3" required>{{ old('affected_systems', $changeRequest?->affected_systems) }}</textarea>
        <x-input-error :messages="$errors->get('affected_systems')" class="mt-2" />
    </div>

    <div>
        <label for="impact_level" class="mb-2 block text-sm font-medium text-slate-700">Impact Level</label>
        <select id="impact_level" name="impact_level" class="w-full rounded-lg border border-slate-300 px-4 py-3" required>
            @foreach (['low', 'medium', 'high'] as $impact)
                <option value="{{ $impact }}" @selected(old('impact_level', $changeRequest?->impact_level ?? 'medium') === $impact)>{{ ucfirst($impact) }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('impact_level')" class="mt-2" />
    </div>
</div>
