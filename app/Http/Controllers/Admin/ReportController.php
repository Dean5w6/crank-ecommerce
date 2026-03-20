<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use App\Exports\ProductExport;
use App\Exports\TransactionExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $stats = [
            'total_revenue' => Transaction::where('status', 'completed')->sum('total_amount'),
            'total_orders' => Transaction::count(),
            'total_products' => Product::count(),
            'recent_sales' => Transaction::with('user')->latest()->take(10)->get(),
        ];

        return view('admin.reports.index', compact('stats'));
    }

    public function exportProducts()
    {
        return Excel::download(new ProductExport, 'products-' . date('Y-m-d') . '.xlsx');
    }

    public function exportTransactions()
    {
        return Excel::download(new TransactionExport, 'transactions-' . date('Y-m-d') . '.xlsx');
    }

    public function downloadReceipt(Transaction $transaction)
    {
        $transaction->load(['user', 'items.product']);
        $pdf = Pdf::loadView('admin.reports.receipt', compact('transaction'));
        return $pdf->download('receipt-' . $transaction->reference_number . '.pdf');
    }
}
