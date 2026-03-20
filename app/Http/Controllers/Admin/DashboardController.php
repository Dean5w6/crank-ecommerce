<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->toDateString());

        $stats = [
            'users' => User::count(),
            'products' => Product::count(),
            'transactions' => Transaction::count(),
            'total_revenue' => Transaction::where('status', 'completed')->sum('total_amount'),
            'categories' => Category::count(),
            'brands' => Brand::count(),
        ];
 
        $yearlySales = Transaction::where('status', 'completed')
            ->whereYear('created_at', date('Y'))
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
 
        $dateRangeSales = Transaction::where('status', 'completed')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
 
        $productSales = TransactionItem::whereHas('transaction', function($query) {
                $query->where('status', 'completed');
            })
            ->select('product_id', DB::raw('SUM(price * quantity) as total'))
            ->groupBy('product_id')   
            ->with('product:id,name')
            ->get();

        return view('admin.dashboard', compact('stats', 'yearlySales', 'dateRangeSales', 'productSales', 'startDate', 'endDate'));
    }
}
