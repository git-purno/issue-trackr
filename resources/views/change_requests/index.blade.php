<x-app-layout>

<x-slot name="header">
<h2 class="text-xl font-semibold">Change Requests</h2>
</x-slot>

<div class="py-6">
<div class="max-w-7xl mx-auto">

<a href="/change-requests/create"
class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block">
New Request
</a>

<table class="w-full border mt-4">

<thead>
<tr>
<th class="border px-4 py-2">Title</th>
<th class="border px-4 py-2">Impact</th>
<th class="border px-4 py-2">Status</th>
<th class="border px-4 py-2">Action</th>
</tr>
</thead>

<tbody>

@foreach($requests as $req)

<tr>

<td class="border px-4 py-2">{{ $req->title }}</td>

<td class="border px-4 py-2">{{ $req->impact_level }}</td>

<td class="border px-4 py-2">{{ $req->status }}</td>

<td class="border px-4 py-2">
    <td>
<span class="px-3 py-1 rounded bg-gray-200">
{{ $req->status }}
</span>
</td>

<form method="POST" action="/change-requests/{{ $req->id }}/approve">
@csrf

<button class="bg-green-600 text-white px-3 py-1 rounded">
Approve
</button>

</form>

</td>

</tr>

@endforeach

</tbody>

</table>

</div>
</div>

</x-app-layout>