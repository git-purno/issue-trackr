<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Issue;
use Illuminate\Support\Facades\Auth;

class IssueController extends Controller
{
    // Show issue submission form
    public function create()
    {
        return view('issues.create');
    }

    // Store new issue
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'priority' => 'required'
        ]);

        Issue::create([
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'status' => 'open',
            'user_id' => Auth::id()
        ]);

        return redirect('/issues')->with('success', 'Issue submitted successfully!');
    }

    // Show all issues
    public function index()
    {
        $issues = Issue::all();
        return view('issues.index', compact('issues'));
    }

    public function assignForm($id)
{
    $issue = Issue::findOrFail($id);
    $engineers = \App\Models\User::where('role','engineer')->get();

    return view('issues.assign', compact('issue','engineers'));
}

public function assign(Request $request, $id)
{
    $issue = Issue::findOrFail($id);

    $issue->assigned_to = $request->engineer_id;
    $issue->status = 'in_progress';

    $issue->save();

    return redirect('/issues')->with('success','Issue assigned successfully');
}

public function statusForm($id)
{
    $issue = Issue::findOrFail($id);

    return view('issues.status', compact('issue'));
}

public function updateStatus(Request $request, $id)
{
    $issue = Issue::findOrFail($id);

    $issue->status = $request->status;

    $issue->save();

    return redirect('/issues')->with('success','Status updated successfully');
}

public function show($id)
{
    $issue = \App\Models\Issue::with(['user','assignedEngineer'])->findOrFail($id);

    return view('issues.show', compact('issue'));
}



}