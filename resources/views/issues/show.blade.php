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

<h3 class="text-lg font-semibold mt-6 mb-3">Comments</h3>

@foreach($issue->comments as $comment)

<div class="bg-gray-100 p-3 rounded mb-2">

<strong>{{ $comment->user->name }}</strong>

<p>{{ $comment->comment }}</p>

</div>

@endforeach
<form method="POST" action="/issues/{{ $issue->id }}/comments">

@csrf

<textarea
name="comment"
class="border p-2 w-full rounded"
placeholder="Write a comment..."
required
></textarea>

<button
type="submit"
class="mt-2 bg-blue-600 text-white px-4 py-2 rounded"
>
Add Comment
</button>

</form>

</x-app-layout>