<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            'Giant',
            'Trek',
            'Specialized',
            'Cannondale',
            'Santa Cruz',
            'Shimano',
            'SRAM',
            'Fox Racing',
            'Oakley',
            'Bell',
        ];

        foreach ($brands as $brand) {
            Brand::create([
                'name' => $brand,
                'slug' => Str::slug($brand),
            ]);
        }
    }
}
