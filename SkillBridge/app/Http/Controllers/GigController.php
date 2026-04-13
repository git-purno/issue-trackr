<?php

namespace App\Http\Controllers;

use App\Models\Gig;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GigController extends Controller
{
    public function index(): View
    {
        return view('gigs.index', ['gigs' => Gig::with('user')->where('status', 'active')->latest()->paginate(12)]);
    }

    public function create(): View
    {
        return view('gigs.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:160'],
            'description' => ['required', 'string', 'max:1600'],
            'price' => ['required', 'integer', 'min:100'],
            'category' => ['required', 'string', 'max:120'],
            'tags' => ['nullable', 'string', 'max:500'],
        ]);

        Gig::create([
            ...$data,
            'user_id' => $request->user()->id,
            'tags' => collect(explode(',', $data['tags'] ?? ''))->map(fn ($tag) => trim($tag))->filter()->values()->all(),
        ]);

        return to_route('gigs.index')->with('status', 'Gig published.');
    }
}
