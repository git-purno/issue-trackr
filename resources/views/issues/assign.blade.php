<x-app-layout>

<div class="max-w-7xl mx-auto py-6 px-4">

<h2 class="text-xl font-bold mb-4">Assign Issue</h2>

<form method="POST" action="/issues/{{ $issue->id }}/assign">
@csrf

<label class="block mb-2">Select Engineer</label>

<select name="engineer_id" class="border p-2 w-full mb-4">

@foreach($engineers as $engineer)

<option value="{{ $engineer->id }}">
{{ $engineer->name }}
</option>

@endforeach

</select>

<button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
Assign Issue
</button>

</form>

</div>

</x-app-layout>