<?php
namespace App\Http\Controllers;

use App\Models\Auction;
use Illuminate\Http\Request;

class AuctionController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->get('sort', 'end_date');

        $auctions = Auction::query()
            ->where('end_date', '>', now())
            ->orderBy($sort)
            ->paginate(12);

        return view('auctions.list', compact('auctions'));
    }
    public function showAuction(Auction $auction)
    {
        $auction->load(['photos', 'bids.user', 'comments.user']);
        return view('auctions.show', compact('auction'));
    }
    public function show($id)
    {
        $auction = Auction::findOrFail($id);
        return view('auctions.show', compact('auction'));
    }
}
