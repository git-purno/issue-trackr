<?php

namespace App\Http\Controllers;

use App\Models\Dispute;
use App\Models\Gig;
use App\Models\JobPost;
use App\Models\Proposal;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        return view('dashboard', [
            'openJobs' => JobPost::where('status', 'open')->latest()->take(5)->get(),
            'myJobs' => JobPost::where('client_id', $user->id)->orWhere('freelancer_id', $user->id)->latest()->take(5)->get(),
            'myProposals' => Proposal::with('job')->where('freelancer_id', $user->id)->latest()->take(5)->get(),
            'gigs' => Gig::where('user_id', $user->id)->latest()->take(4)->get(),
            'escrowTotal' => Transaction::where('payer_id', $user->id)->where('status', 'held')->sum('amount'),
            'disputeCount' => Dispute::where('raised_by', $user->id)->where('status', 'open')->count(),
        ]);
    }

    public function admin(): View
    {
        return view('admin.dashboard', [
            'users' => User::with('verifications')->latest()->take(20)->get(),
            'pendingVerifications' => User::whereHas('verifications', fn ($query) => $query->where('status', 'pending'))->with('verifications')->get(),
            'disputes' => Dispute::with('job')->where('status', 'open')->latest()->get(),
            'stats' => [
                'users' => User::count(),
                'jobs' => JobPost::count(),
                'open_jobs' => JobPost::where('status', 'open')->count(),
                'escrow' => Transaction::where('status', 'held')->sum('amount'),
            ],
        ]);
    }
}
