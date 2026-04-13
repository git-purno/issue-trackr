<?php

namespace App\Http\Controllers;

use App\Models\JobPost;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MessageController extends Controller
{
    public function thread(Request $request, User $user): View
    {
        $messages = Message::where(function ($query) use ($request, $user) {
            $query->where('sender_id', $request->user()->id)->where('receiver_id', $user->id);
        })->orWhere(function ($query) use ($request, $user) {
            $query->where('sender_id', $user->id)->where('receiver_id', $request->user()->id);
        })->oldest()->get();

        return view('messages.thread', ['peer' => $user, 'messages' => $messages, 'jobs' => JobPost::latest()->take(8)->get()]);
    }

    public function send(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'body' => ['required_without:attachment', 'nullable', 'string', 'max:2000'],
            'job_id' => ['nullable', 'exists:jobs,id'],
            'attachment' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf,doc,docx', 'max:4096'],
        ]);

        Message::create([
            'sender_id' => $request->user()->id,
            'receiver_id' => $user->id,
            'job_id' => $data['job_id'] ?? null,
            'body' => $data['body'] ?? null,
            'attachment_path' => $request->file('attachment')?->store('private/messages'),
        ]);

        return back()->with('status', 'Message sent.');
    }
}
