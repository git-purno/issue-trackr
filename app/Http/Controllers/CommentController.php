<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Issue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Issue $issue)
    {
        $user = Auth::user();

        abort_unless(
            $user->hasRole('admin', 'manager', 'analyst') ||
            $issue->user_id === $user->id ||
            $issue->assigned_to === $user->id,
            403
        );

        $validated = $request->validate([
            'comment' => ['required', 'string'],
        ]);

        Comment::create([
            'issue_id' => $issue->id,
            'user_id' => $user->id,
            'comment' => $validated['comment'],
        ]);

        return redirect()
            ->route('issues.show', $issue)
            ->with('success', 'Comment added successfully.');
    }
}
