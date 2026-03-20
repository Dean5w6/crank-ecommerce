<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransactionExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Transaction::with('user')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Reference Number',
            'Customer Name',
            'Total Amount',
            'Status',
            'Created At',
        ];
    }

    public function map($transaction): array
    {
        return [
            $transaction->id,
            $transaction->reference_number,
            $transaction->user->name,
            $transaction->total_amount,
            $transaction->status,
            $transaction->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
