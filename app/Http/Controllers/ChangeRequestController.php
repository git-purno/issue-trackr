<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChangeRequest;
use Illuminate\Support\Facades\Auth;

class ChangeRequestController extends Controller
{

    // 📋 Show all requests
    public function index()
    {
        $requests = ChangeRequest::all();
        return view('change_requests.index', compact('requests'));
    }

    // 📝 Show form
    public function create()
    {
        return view('change_requests.create');
    }

    // 💾 Store request
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'justification' => 'required',
            'risk_analysis' => 'required',
            'affected_systems' => 'required',
            'impact_level' => 'required'
        ]);

        ChangeRequest::create([
            'title' => $request->title,
            'description' => $request->description,
            'justification' => $request->justification,
            'risk_analysis' => $request->risk_analysis,
            'affected_systems' => $request->affected_systems,
            'impact_level' => $request->impact_level,
            'user_id' => Auth::id()
        ]);

        return redirect('/change-requests')->with('success','Request submitted');
    }

    // ✅ Approval workflow
    public function approve($id)
    {
        $changeRequest = ChangeRequest::findOrFail($id); // ✅ renamed variable

        $user = auth()->user();

        if ($user->role == 'analyst' && $changeRequest->status == 'submitted') {
            $changeRequest->status = 'analyst_approved';
            $changeRequest->analyst_id = $user->id;
            $changeRequest->analyst_approved_at = now();
        }

        elseif ($user->role == 'manager' && $changeRequest->status == 'analyst_approved') {
            $changeRequest->status = 'manager_approved';
            $changeRequest->manager_id = $user->id;
            $changeRequest->manager_approved_at = now();
        }

        elseif ($user->role == 'admin' && $changeRequest->status == 'manager_approved') {
            $changeRequest->status = 'admin_approved';
            $changeRequest->admin_id = $user->id;
            $changeRequest->admin_approved_at = now();
        }

        $changeRequest->save();

        return back()->with('success','Approved successfully');
    }
}