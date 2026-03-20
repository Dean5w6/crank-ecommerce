<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Mail\TransactionStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CartController extends Controller
{ 
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('storefront.cart', compact('cart', 'total'));
    }
 
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $request->quantity;
        } else {
            $cart[$product->id] = [
                "name" => $product->name,
                "quantity" => $request->quantity,
                "price" => $product->price,
                "photo" => $product->photos[0] ?? null,
                "slug" => $product->slug
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }
 
    public function remove(Request $request)
    {
        if ($request->id) {
            $cart = session()->get('cart');
            if (isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            return redirect()->back()->with('success', 'Item removed from cart.');
        }
    }
 
    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('products.index')->with('error', 'Your cart is empty.');
        }

        try {
            DB::beginTransaction();

            $total = 0;
            foreach ($cart as $item) {
                $total += $item['price'] * $item['quantity'];
            }
 
            $transaction = Transaction::create([
                'user_id' => auth()->id(),
                'total_amount' => $total,
                'status' => 'pending',
                'reference_number' => 'CRANK-' . strtoupper(Str::random(10)),
            ]);
 
            foreach ($cart as $id => $details) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $id,
                    'quantity' => $details['quantity'],
                    'price' => $details['price'],
                ]);
 
                $product = Product::find($id);
                if ($product) {
                    $product->decrement('stock', $details['quantity']);
                }
            }

            DB::commit();
 
            try {
                Mail::to(auth()->user()->email)->send(new TransactionStatusUpdated($transaction));
            } catch (\Exception $e) { 
                \Illuminate\Support\Facades\Log::error('Email failed: ' . $e->getMessage());
            }
 
            session()->forget('cart');

            $redirectRoute = auth()->user()->isAdmin() ? 'admin.transactions.index' : 'customer.dashboard';
            return redirect()->route($redirectRoute)->with('success', 'Order placed successfully! Reference: ' . $transaction->reference_number);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Checkout failed: ' . $e->getMessage());
        }
    }
}
