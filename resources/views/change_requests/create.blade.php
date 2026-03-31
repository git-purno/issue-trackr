<x-app-layout>

<x-slot name="header">
<h2 class="text-xl font-semibold">Submit Change Request</h2>
</x-slot>

<div class="py-6">
<div class="max-w-4xl mx-auto">

<form method="POST" action="/change-requests" class="space-y-4">
@csrf

<input name="title" placeholder="Title" class="border p-2 w-full rounded">

<textarea name="description" placeholder="Description" class="border p-2 w-full rounded"></textarea>

<textarea name="justification" placeholder="Justification" class="border p-2 w-full rounded"></textarea>

<textarea name="risk_analysis" placeholder="Risk Analysis" class="border p-2 w-full rounded"></textarea>

<textarea name="affected_systems" placeholder="Affected Systems" class="border p-2 w-full rounded"></textarea>

<select name="impact_level" class="border p-2 w-full rounded">
<option value="low">Low</option>
<option value="medium">Medium</option>
<option value="high">High</option>
</select>

<button class="bg-blue-600 text-white px-4 py-2 rounded">
Submit Request
</button>

</form>

</div>
</div>

</x-app-layout>