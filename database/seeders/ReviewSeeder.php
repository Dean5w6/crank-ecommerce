<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::where('role', 'customer')->get();
        $products = Product::all();

        if ($customers->isEmpty() || $products->isEmpty()) return;
 
        for ($i = 1; $i <= 10; $i++) {
            Review::create([
                'user_id' => $customers->random()->id,
                'product_id' => $products->random()->id,
                'rating' => rand(4, 5),
                'comment' => 'Fantastic quality! Highly recommended for any cycling enthusiast.',
            ]);
        }
    }
}
