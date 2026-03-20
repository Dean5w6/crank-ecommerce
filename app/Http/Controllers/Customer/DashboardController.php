<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $stats = [
            'transactions' => $user->transactions()->count(),
            'total_spent' => $user->transactions()->where('status', 'completed')->sum('total_amount'),
            'reviews' => $user->reviews()->count(),
        ];

        $recent_transactions = $user->transactions()->latest()->take(5)->get();

        return view('customer.dashboard', compact('stats', 'recent_transactions'));
    }
  
    public function show(Transaction $transaction)
    { 
        if ($transaction->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        } 
        
        $transaction->load(['items.product']);

        return view('customer.transactions.show', compact('transaction'));
    }
}
