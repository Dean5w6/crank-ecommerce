<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::where('role', 'customer')->get();
        $products = Product::all();

        if ($customers->isEmpty() || $products->isEmpty()) return;
 
        for ($i = 1; $i <= 10; $i++) {
            $customer = $customers->random();
            $transaction = Transaction::create([
                'user_id' => $customer->id,
                'total_amount' => 0,
                'status' => 'completed',
                'reference_number' => 'CRANK-' . strtoupper(Str::random(10)),
                'created_at' => now()->subDays(rand(1, 60)),  
            ]);

            $total = 0; 
            $randomProducts = $products->random(rand(1, 3));
            foreach ($randomProducts as $product) {
                $qty = rand(1, 2);
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'price' => $product->price,
                ]);
                $total += $product->price * $qty;
            }

            $transaction->update(['total_amount' => $total]);
        }
    }
}
