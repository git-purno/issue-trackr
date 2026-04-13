<?php

namespace App\Http\Controllers;

use App\Models\Verification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile.edit', ['user' => $request->user()->load('profile', 'verifications')]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'bio' => ['nullable', 'string', 'max:1200'],
            'skills' => ['nullable', 'string', 'max:500'],
            'portfolio_links' => ['nullable', 'string', 'max:800'],
            'university' => ['nullable', 'string', 'max:160'],
            'department' => ['nullable', 'string', 'max:160'],
            'city' => ['nullable', 'string', 'max:80'],
        ]);

        $request->user()->profile()->updateOrCreate(
            ['user_id' => $request->user()->id],
            [
                'bio' => $data['bio'] ?? null,
                'skills' => $this->splitList($data['skills'] ?? ''),
                'portfolio_links' => $this->splitList($data['portfolio_links'] ?? ''),
                'university' => $data['university'] ?? null,
                'department' => $data['department'] ?? null,
                'city' => $data['city'] ?? null,
            ]
        );

        $request->user()->recalculateProfileCompletion();

        return back()->with('status', 'Profile updated.');
    }

    public function verifyEmail(Request $request): RedirectResponse
    {
        $request->user()->forceFill(['email_verified_at' => now()])->save();
        $request->user()->recalculateTrustScore();

        return back()->with('status', 'Email verified in demo mode.');
    }

    public function verifyPhone(Request $request): RedirectResponse
    {
        $request->validate(['otp' => ['required', 'digits:6']]);
        $request->user()->forceFill(['phone_verified_at' => now()])->save();
        $request->user()->recalculateTrustScore();

        return back()->with('status', 'Phone OTP accepted in demo mode.');
    }

    public function submitVerification(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'type' => ['required', 'in:student,nid'],
            'university_email' => ['nullable', 'email'],
            'document' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
        ]);

        $path = $request->file('document')?->store('private/verifications');

        Verification::updateOrCreate(
            ['user_id' => $request->user()->id, 'type' => $data['type']],
            [
                'status' => 'pending',
                'document_path' => $path,
                'university_email' => $data['university_email'] ?? null,
                'notes' => 'Awaiting manual admin review.',
            ]
        );

        return back()->with('status', 'Verification submitted for admin review.');
    }

    private function splitList(string $value): array
    {
        return collect(explode(',', $value))->map(fn ($item) => trim($item))->filter()->values()->all();
    }
}
