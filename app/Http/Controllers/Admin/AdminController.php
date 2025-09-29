<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\MarketBook;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Get dashboard statistics for admin.
     */
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_market_books' => MarketBook::count(),
            'available_books' => MarketBook::where('is_available', true)->count(),
            'total_transactions' => Transaction::count(),
            'pending_transactions' => Transaction::where('status', 'pending')->count(),
            'completed_transactions' => Transaction::where('status', 'completed')->count(),
            'gift_transactions' => Transaction::where('type', 'gift')->count(),
            'exchange_transactions' => Transaction::where('type', 'exchange')->count(),
        ];

        // Recent transactions
        $recent_transactions = Transaction::with(['marketBook.owner', 'requester'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Books by condition
        $books_by_condition = MarketBook::select('condition', DB::raw('count(*) as count'))
            ->groupBy('condition')
            ->get();

        // Monthly transaction trends (last 6 months)
        $monthly_trends = Transaction::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return view('backoffice.dashboard', compact(
            'stats',
            'recent_transactions',
            'books_by_condition',
            'monthly_trends'
        ));
    }

    /**
     * Get system health and performance metrics.
     */
    public function systemHealth(): JsonResponse
    {
        $health = [
            'database_connected' => true,
            'storage_available' => is_writable(storage_path()),
            'cache_working' => true,
        ];

        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            $health['database_connected'] = false;
        }

        return response()->json($health);
    }
}
