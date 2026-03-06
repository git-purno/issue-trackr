<x-app-layout>

<div class="max-w-4xl mx-auto py-6 px-4">

<h2 class="text-xl font-bold mb-4">Update Issue Status</h2>

<form method="POST" action="/issues/{{ $issue->id }}/status">
@csrf

<label class="block mb-2 font-semibold">Select Status</label>

<select name="status" class="border p-2 w-full mb-4 rounded">

<option value="open">Open</option>
<option value="in_progress">In Progress</option>
<option value="resolved">Resolved</option>
<option value="closed">Closed</option>

</select>

<button type="submit"
style="background-color:#16a34a; color:white; padding:10px 20px; border-radius:6px; font-weight:600;">
Update Status
</button>

</form>

</div>

</x-app-layout>