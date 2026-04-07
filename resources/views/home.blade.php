<x-app-layout>

<div class="py-12">
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

<!-- HERO -->
<div class="surface-card overflow-hidden mb-6">
<div class="p-8 md:p-10">
<div class="flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">
<div class="max-w-3xl">
<span class="soft-badge mb-4">Operations Workspace</span>

<h1 class="text-3xl md:text-4xl font-bold tracking-tight mb-4 text-slate-900">
Issue and Change Request Management System
</h1>

<p class="text-slate-600 text-lg leading-7 mb-6">
Manage operational requests, approvals, and team coordination from one streamlined workspace.
</p>

<div class="flex flex-wrap gap-3">
<a href="/issues"
class="bg-blue-600 text-white px-6 py-2.5 rounded-lg hover:bg-blue-700">
View Issues
</a>

<a href="/issues/create"
class="bg-green-600 text-white px-6 py-2.5 rounded-lg hover:bg-green-700">
Submit Issue
</a>

<a href="/change-requests"
class="bg-white text-slate-700 border border-slate-200 px-6 py-2.5 rounded-lg hover:bg-slate-50">
Change Requests
</a>

</div>
</div>

<div class="grid grid-cols-2 gap-4 lg:min-w-[320px]">
<div class="rounded-xl bg-slate-50 border border-slate-200 p-5">
<p class="text-sm text-slate-500 mb-1">Request Visibility</p>
<p class="text-3xl font-bold text-slate-900">Live</p>
</div>

<div class="rounded-xl bg-slate-50 border border-slate-200 p-5">
<p class="text-sm text-slate-500 mb-1">Approval Flow</p>
<p class="text-3xl font-bold text-slate-900">Tracked</p>
</div>

<div class="rounded-xl bg-slate-50 border border-slate-200 p-5 col-span-2">
<p class="text-sm text-slate-500 mb-2">Workspace Focus</p>
<p class="text-base font-semibold text-slate-800">Clear intake, accountable ownership, controlled change execution</p>
</div>
</div>
</div>
</div>
</div>

<!-- HIGHLIGHTS -->
<div class="grid gap-6 lg:grid-cols-3">

<div class="module-card">
<span class="soft-badge mb-4">Intake</span>
<h3 class="text-lg font-semibold text-slate-900 mb-2">Structured request capture</h3>
<p class="text-sm leading-6 text-slate-600">
Create and organize incoming work with clear details, ownership context, and supporting information.
</p>
</div>

<div class="module-card">
<span class="soft-badge mb-4">Execution</span>
<h3 class="text-lg font-semibold text-slate-900 mb-2">Controlled delivery workflow</h3>
<p class="text-sm leading-6 text-slate-600">
Keep work moving through review, action, and closure with visible status and dependable process control.
</p>
</div>

<div class="module-card">
<span class="soft-badge mb-4">Oversight</span>
<h3 class="text-lg font-semibold text-slate-900 mb-2">Operational visibility</h3>
<p class="text-sm leading-6 text-slate-600">
Support managers and teams with concise dashboards, timely updates, and stronger decision confidence.
</p>
</div>

</div>

</div>
</div>

</x-app-layout>
