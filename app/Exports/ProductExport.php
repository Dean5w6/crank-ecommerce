<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Product::with(['category', 'brand'])->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Category',
            'Brand',
            'Price',
            'Stock',
            'Created At',
        ];
    }

    public function map($product): array
    {
        return [
            $product->id,
            $product->name,
            $product->category->name,
            $product->brand->name,
            $product->price,
            $product->stock,
            $product->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
