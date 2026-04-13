<?php

namespace App\Http\Controllers;

use App\Models\JobPost;
use App\Models\Proposal;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class JobController extends Controller
{
    public function index(Request $request): View
    {
        $jobs = JobPost::with('client')
            ->when($request->filled('q'), fn ($query) => $query->where('title', 'like', '%'.$request->q.'%'))
            ->when($request->filled('category'), fn ($query) => $query->where('category', $request->category))
            ->latest()
            ->paginate(9)
            ->withQueryString();

        return view('jobs.index', ['jobs' => $jobs]);
    }

    public function create(): View
    {
        return view('jobs.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'description' => ['required', 'string', 'max:3000'],
            'budget' => ['required', 'integer', 'min:100', 'max:500000'],
            'deadline' => ['required', 'date', 'after:today'],
            'category' => ['nullable', 'string', 'max:120'],
            'skills' => ['nullable', 'string', 'max:500'],
        ]);

        $job = JobPost::create([
            ...$data,
            'client_id' => $request->user()->id,
            'skills' => $this->splitList($data['skills'] ?? ''),
        ]);

        Transaction::create([
            'job_id' => $job->id,
            'payer_id' => $request->user()->id,
            'amount' => $job->budget,
            'provider' => $request->input('payment_provider', 'mock_bkash'),
            'reference' => 'SB-'.Str::upper(Str::random(10)),
            'status' => 'held',
        ]);

        return to_route('jobs.show', $job)->with('status', 'Job posted and mock escrow funded.');
    }

    public function show(JobPost $job): View
    {
        return view('jobs.show', ['job' => $job->load('client.profile', 'freelancer', 'proposals.freelancer.profile')]);
    }

    public function apply(Request $request, JobPost $job): RedirectResponse
    {
        abort_if($job->status !== 'open', 403, 'Only open jobs accept proposals.');

        $data = $request->validate([
            'cover_letter' => ['required', 'string', 'max:2500'],
            'bid_amount' => ['required', 'integer', 'min:100'],
            'delivery_days' => ['required', 'integer', 'min:1', 'max:60'],
        ]);

        Proposal::updateOrCreate(
            ['job_id' => $job->id, 'freelancer_id' => $request->user()->id],
            $data + ['status' => 'pending']
        );

        return back()->with('status', 'Proposal submitted.');
    }

    public function accept(Request $request, JobPost $job, Proposal $proposal): RedirectResponse
    {
        abort_unless($request->user()->id === $job->client_id || $request->user()->role === 'admin', 403);
        abort_unless($proposal->job_id === $job->id, 404);

        $proposal->update(['status' => 'accepted']);
        $job->update(['freelancer_id' => $proposal->freelancer_id, 'status' => 'in_progress']);
        $job->proposals()->whereKeyNot($proposal->id)->update(['status' => 'rejected']);

        return back()->with('status', 'Freelancer accepted and job moved to in progress.');
    }

    public function complete(Request $request, JobPost $job): RedirectResponse
    {
        abort_unless(in_array($request->user()->id, [$job->client_id, $job->freelancer_id]) || $request->user()->role === 'admin', 403);

        $job->update(['status' => 'completed']);
        Transaction::where('job_id', $job->id)->where('status', 'held')->update([
            'status' => 'released',
            'payee_id' => $job->freelancer_id,
        ]);
        $job->freelancer?->recalculateTrustScore();
        $job->client?->recalculateTrustScore();

        return back()->with('status', 'Job completed and escrow released in mock payment mode.');
    }

    public function generateProposal(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'job_title' => ['required', 'string', 'max:160'],
            'skills' => ['nullable', 'string', 'max:300'],
        ]);

        $proposal = "Hi, I can help with {$data['job_title']}. My approach is to confirm the scope, deliver a first draft early, and revise quickly based on your feedback. I have relevant skills in ".($data['skills'] ?: 'research, communication, and delivery')." and can keep the work clear, documented, and on time.";

        return back()->with('generated_proposal', $proposal);
    }

    private function splitList(string $value): array
    {
        return collect(explode(',', $value))->map(fn ($item) => trim($item))->filter()->values()->all();
    }
}
