<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{

public function store(Request $request, $issue_id)
{

$request->validate([
'comment' => 'required'
]);

Comment::create([
'issue_id' => $issue_id,
'user_id' => Auth::id(),
'comment' => $request->comment
]);

return back();

}

}