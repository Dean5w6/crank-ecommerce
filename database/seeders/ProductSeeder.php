<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();
        $brands = Brand::all();

        $productData = [
            ['name' => 'Giant Anthem Pro', 'price' => 145000, 'desc' => 'Top-tier XC mountain bike.'],
            ['name' => 'Trek Fuel EX', 'price' => 115000, 'desc' => 'Versatile trail bike.'],
            ['name' => 'Specialized Tarmac', 'price' => 185000, 'desc' => 'Aerodynamic road bike.'],
            ['name' => 'Giant TCR Advanced', 'price' => 85000, 'desc' => 'High-performance road bike.'],
            ['name' => 'Shimano XT Groupset', 'price' => 28000, 'desc' => 'MTB groupset.'],
            ['name' => 'Cannondale SuperSix', 'price' => 165000, 'desc' => 'Fastest road bike.'],
            ['name' => 'Santa Cruz Nomad', 'price' => 220000, 'desc' => 'Enduro mountain bike.'],
            ['name' => 'Trek Domane SL', 'price' => 125000, 'desc' => 'Endurance road bike.'],
            ['name' => 'Giant Trance X', 'price' => 95000, 'desc' => 'All-mountain trail bike.'],
            ['name' => 'Specialized Stumpjumper', 'price' => 135000, 'desc' => 'The ultimate trail bike.'],
        ];

        foreach ($productData as $index => $data) {
            Product::create([
                'name' => $data['name'],
                'slug' => Str::slug($data['name']),
                'description' => $data['desc'],
                'price' => $data['price'],
                'stock' => rand(5, 20),
                'category_id' => $categories->random()->id,
                'brand_id' => $brands->random()->id,
                'photos' => ['products/product_placeholder.png'],  
            ]);
        }
    }
}
