<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Comments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Auction $auction)
    {
        $request->validate([
            'content' => 'required|string|max:1000'
        ]);

        $comment = $auction->comments()->create([
            'user_id' => Auth::id(),
            'content' => $request->content
        ]);

        return response()->json($comment->load('user'));
    }

    public function index(Auction $auction)
    {
        return $auction->comments()->with('user')->latest()->get();
    }
}
