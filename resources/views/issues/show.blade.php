<x-app-layout>

<div class="max-w-4xl mx-auto py-6 px-4">

<h2 class="text-2xl font-bold mb-6">Issue Details</h2>

<div class="bg-white shadow p-6 rounded">

<p class="mb-3">
<strong>Title:</strong> {{ $issue->title }}
</p>

<p class="mb-3">
<strong>Description:</strong> {{ $issue->description }}
</p>

<p class="mb-3">
<strong>Priority:</strong> {{ ucfirst($issue->priority) }}
</p>

<p class="mb-3">
<strong>Status:</strong> {{ ucfirst($issue->status) }}
</p>

<p class="mb-3">
<strong>Created By:</strong> {{ $issue->user->name }}
</p>

<p class="mb-3">
<strong>Assigned Engineer:</strong>
{{ $issue->assignedEngineer ? $issue->assignedEngineer->name : 'Not Assigned' }}
</p>

</div>

</div>

</x-app-layout>