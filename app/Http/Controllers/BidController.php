<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Bid;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class BidController extends Controller
{
    public function store(Request $request, Auction $auction)
    {
        $request->validate([
            'amount' => 'required|numeric|min:'.($auction->current_bid)
        ]);

        if (Carbon::parse($auction->end_date)->isPast()) {
            return response()->json(['message' => 'Аукцион уже завершен'], 422);
        }
        $bid = $auction->bids()->create([
            'user_id' => Auth::id(),
            'amount' => $request->amount
        ]);

        $auction->update(['current_bid' => $request->amount]);

        return response()->json([
            'message' => 'Ставка принята',
            'newBid' => $bid->load('user')
        ]);
    }

    public function index(Auction $auction)
    {
        return $auction->bids()->with('user')->latest()->take(10)->get();
    }
}
