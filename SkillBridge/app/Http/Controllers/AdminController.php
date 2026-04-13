<?php

namespace App\Http\Controllers;

use App\Models\Dispute;
use App\Models\User;
use App\Models\Verification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function approveVerification(Request $request, Verification $verification): RedirectResponse
    {
        abort_unless($request->user()->role === 'admin', 403);

        $verification->update(['status' => 'approved', 'verified_at' => now(), 'notes' => 'Approved by admin.']);
        $verification->user->recalculateTrustScore();

        return back()->with('status', 'Verification approved.');
    }

    public function rejectVerification(Request $request, Verification $verification): RedirectResponse
    {
        abort_unless($request->user()->role === 'admin', 403);

        $verification->update(['status' => 'rejected', 'notes' => $request->input('notes', 'Rejected by admin.')]);
        $verification->user->recalculateTrustScore();

        return back()->with('status', 'Verification rejected.');
    }

    public function suspend(Request $request, User $user): RedirectResponse
    {
        abort_unless($request->user()->role === 'admin', 403);

        $user->update(['status' => 'suspended', 'suspended_at' => now()]);

        return back()->with('status', 'Account suspended.');
    }

    public function resolveDispute(Request $request, Dispute $dispute): RedirectResponse
    {
        abort_unless($request->user()->role === 'admin', 403);

        $data = $request->validate(['resolution' => ['required', 'string', 'max:2000']]);
        $dispute->update(['status' => 'resolved', 'assigned_admin_id' => $request->user()->id, 'resolution' => $data['resolution']]);

        return back()->with('status', 'Dispute resolved.');
    }
}
