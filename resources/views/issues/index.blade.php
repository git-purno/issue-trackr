<x-app-layout>

<x-slot name="header">
<div class="flex justify-between items-center">

<h2 class="font-semibold text-xl text-gray-800 leading-tight">
All Issues
</h2>

<a href="/issues/create"
class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
Create Issue
</a>

</div>
</x-slot>


<div class="py-6">
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

<div class="bg-white shadow-sm sm:rounded-lg p-6">

<table class="min-w-full border border-gray-300">

<thead class="bg-gray-100">
<tr>
<th class="border px-4 py-2">Title</th>
<th class="border px-4 py-2">Priority</th>
<th class="border px-4 py-2">Status</th>
<th class="border px-4 py-2">Assign</th>
<th class="border px-4 py-2">Update</th>
<th class="border px-4 py-2">View</th>
</tr>
</thead>

<tbody>

@foreach($issues as $issue)

<tr class="hover:bg-gray-50">

<td class="border px-4 py-2">{{ $issue->title }}</td>

<td class="border px-4 py-2 capitalize">
{{ $issue->priority }}
</td>

<td class="border px-4 py-2">

@if($issue->status == 'open')
<span style="background:#facc15;color:#000;padding:5px 12px;border-radius:6px;font-weight:bold;">
Open
</span>

@elseif($issue->status == 'in_progress')
<span style="background:#2563eb;color:white;padding:5px 12px;border-radius:6px;font-weight:bold;">
In Progress
</span>

@elseif($issue->status == 'resolved')
<span style="background:#16a34a;color:white;padding:5px 12px;border-radius:6px;font-weight:bold;">
Resolved
</span>

@elseif($issue->status == 'closed')
<span style="background:#374151;color:white;padding:5px 12px;border-radius:6px;font-weight:bold;">
Closed
</span>

@endif

</td>

<td class="border px-4 py-2 text-center">
<a href="/issues/{{ $issue->id }}/assign"
class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded">
Assign
</a>
</td>

<td class="border px-4 py-2 text-center">
<a href="/issues/{{ $issue->id }}/status"
class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded">
Update
</a>
</td>

<td class="border px-4 py-2 text-center">
<a href="/issues/{{ $issue->id }}"
class="bg-gray-700 hover:bg-gray-800 text-white px-3 py-1 rounded">
View
</a>
</td>

</tr>

@endforeach

</tbody>

</table>

</div>

</div>
</div>

</x-app-layout>