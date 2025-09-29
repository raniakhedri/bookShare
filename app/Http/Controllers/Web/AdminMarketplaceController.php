<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\MarketBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminMarketplaceController extends Controller
{
    public function index()
    {
        $marketBooks = MarketBook::with(['owner'])
            ->withCount(['transactions as requests_count'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $totalRequests = MarketBook::withCount('transactions')
            ->get()
            ->sum('transactions_count');

        return view('backoffice.frontoffice.marketplace', compact('marketBooks', 'totalRequests'));
    }

    public function destroy($id)
    {
        $marketBook = MarketBook::findOrFail($id);
        
        // Delete the image if it exists
        if ($marketBook->image_path) {
            Storage::disk('public')->delete($marketBook->image_path);
        }
        
        $marketBook->delete();
        
        return redirect()->back()->with('success', 'Livre supprimé avec succès');
    }
}