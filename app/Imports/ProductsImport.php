<?php

namespace App\Imports;

use App\Models\Product;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToModel, WithHeadingRow
{ 
    public function model(array $row)
    {
        return new Product([
            'name'        => $row['name'],
            'slug'        => Str::slug($row['name']),
            'description' => $row['description'],
            'price'       => $row['price'],
            'stock'       => $row['stock'],
            'category_id' => $row['category_id'],
            'brand_id'    => $row['brand_id'],
            'photos'      => ['products/product_placeholder.png'],
        ]);
    }
}
