<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4 mb-6">
                <div class="surface-card p-6">
                    <p class="text-sm text-slate-500 mb-2">Assigned Tasks</p>
                    <p class="text-3xl font-bold text-slate-900">12</p>
                    <p class="text-sm text-slate-600 mt-2">Across issues and change requests.</p>
                </div>

                <div class="surface-card p-6">
                    <p class="text-sm text-slate-500 mb-2">Open Issues</p>
                    <p class="text-3xl font-bold text-slate-900">28</p>
                    <p class="text-sm text-slate-600 mt-2">Includes active and on-hold work items.</p>
                </div>

                <div class="surface-card p-6">
                    <p class="text-sm text-slate-500 mb-2">Pending Approvals</p>
                    <p class="text-3xl font-bold text-slate-900">6</p>
                    <p class="text-sm text-slate-600 mt-2">Awaiting analyst, manager, or admin action.</p>
                </div>

                <div class="surface-card p-6">
                    <p class="text-sm text-slate-500 mb-2">Scheduled Changes</p>
                    <p class="text-3xl font-bold text-slate-900">4</p>
                    <p class="text-sm text-slate-600 mt-2">Approved requests lined up for rollout.</p>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-3">
                <div class="surface-card p-6 lg:col-span-2">
                    <div class="flex items-center justify-between mb-5">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Overview</p>
                            <h3 class="text-xl font-semibold text-slate-900 mt-1">Operational summary</h3>
                        </div>
                        <span class="soft-badge">Workspace status</span>
                    </div>

                    <div class="space-y-4">
                        <div class="feature-item">
                            <p class="font-semibold text-slate-800 mb-1">Work intake</p>
                            <p>Requests enter the system with consistent detail so teams can act quickly and clearly.</p>
                        </div>

                        <div class="feature-item">
                            <p class="font-semibold text-slate-800 mb-1">Approval control</p>
                            <p>Review paths remain visible, accountable, and easier to manage across teams.</p>
                        </div>

                        <div class="feature-item">
                            <p class="font-semibold text-slate-800 mb-1">Delivery oversight</p>
                            <p>Dashboards and updates keep ongoing work aligned without adding noise to the interface.</p>
                        </div>
                    </div>
                </div>

                <div class="surface-card p-6">
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500 mb-4">Quick Actions</p>
                    <div class="space-y-3">
                        <a href="/issues/create" class="block rounded-lg bg-green-600 px-4 py-3 text-center font-medium text-white hover:bg-green-700">
                            Submit New Issue
                        </a>

                        <a href="/issues" class="block rounded-lg bg-blue-600 px-4 py-3 text-center font-medium text-white hover:bg-blue-700">
                            Review Issues
                        </a>

                        <a href="/change-requests/create" class="block rounded-lg border border-slate-200 bg-white px-4 py-3 text-center font-medium text-slate-700 hover:bg-slate-50">
                            Create Change Request
                        </a>

                        <a href="/change-requests" class="block rounded-lg border border-slate-200 bg-white px-4 py-3 text-center font-medium text-slate-700 hover:bg-slate-50">
                            View Approvals Queue
                        </a>
                    </div>
                </div>

                <div class="surface-card p-6 lg:col-span-3">
                    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Highlights</p>
                            <h3 class="text-xl font-semibold text-slate-900 mt-1">Key workspace strengths</h3>
                        </div>
                        <span class="soft-badge">Operational view</span>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4 mt-6">
                        <div class="feature-item">
                            <p class="font-semibold text-slate-800 mb-1">Consistency</p>
                            <p>Standardized process reduces ambiguity across requests and approvals.</p>
                        </div>

                        <div class="feature-item">
                            <p class="font-semibold text-slate-800 mb-1">Accountability</p>
                            <p>Clear ownership and tracked decisions support better follow-through.</p>
                        </div>

                        <div class="feature-item">
                            <p class="font-semibold text-slate-800 mb-1">Visibility</p>
                            <p>Teams can quickly understand current workload, pending items, and priorities.</p>
                        </div>

                        <div class="feature-item">
                            <p class="font-semibold text-slate-800 mb-1">Control</p>
                            <p>Operational decisions are easier to guide with a calmer, more focused interface.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
