<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;

class StorefrontController extends Controller
{ 
    public function products(Request $request)
    {
        $search = $request->input('search');

        $products = Product::search($search)
            ->query(function ($query) use ($request) {
                $query->with(['category', 'brand']);
 
                if ($request->has('category') && $request->category != '') {
                    $query->whereHas('category', function($q) use ($request) {
                        $q->where('slug', $request->category);
                    });
                }
 
                if ($request->has('brand') && $request->brand != '') {
                    $query->whereHas('brand', function($q) use ($request) {
                        $q->where('slug', $request->brand);
                    });
                }
 
                if ($request->has('min_price') && $request->min_price != '') {
                    $query->where('price', '>=', $request->min_price);
                }
                if ($request->has('max_price') && $request->max_price != '') {
                    $query->where('price', '<=', $request->max_price);
                }
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $categories = Category::all();
        $brands = Brand::all();

        return view('storefront.products.index', compact('products', 'categories', 'brands'));
    }
 
    public function productDetail(Product $product)
    {
        $product->load(['category', 'brand', 'reviews.user']);
        $related_products = Product::where('category_id', $product->category_id)
                                    ->where('id', '!=', $product->id)
                                    ->take(4)
                                    ->get();

        $canReview = false;
        if (auth()->check()) {
            $canReview = auth()->user()->transactions()
                ->where('status', 'completed')
                ->whereHas('items', function ($query) use ($product) {
                    $query->where('product_id', $product->id);
                })
                ->exists();
        }

        return view('storefront.products.show', compact('product', 'related_products', 'canReview'));
    }
}
