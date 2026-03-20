<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Mail\TransactionStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\Facades\DataTables;

class TransactionController extends Controller
{ 
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Transaction::with('user')->latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('user_name', function($row){
                    return $row->user->name;
                })
                ->addColumn('action', function($row){
                    $btn = '<a href="'.route('admin.transactions.show', $row->id).'" class="view btn btn-info btn-sm">View</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.transactions.index');
    } 

    public function show(Transaction $transaction)
    {
        $transaction->load(['user', 'items.product']);
        return view('admin.transactions.show', compact('transaction'));
    }
 
    public function updateStatus(Request $request, Transaction $transaction)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $transaction->update(['status' => $request->status]);
 
        try {
            Mail::to($transaction->user->email)->send(new TransactionStatusUpdated($transaction));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Admin status update email failed: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Transaction status updated successfully.');
    }
 
    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        return redirect()->route('admin.transactions.index')->with('success', 'Transaction deleted successfully.');
    }
}
