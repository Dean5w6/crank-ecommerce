<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Imports\ProductsImport;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{ 
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Product::with(['category', 'brand'])->latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<a href="'.route('admin.products.edit', $row->id).'" class="edit btn btn-primary btn-sm">Edit</a>';
                    $btn .= ' <form action="'.route('admin.products.destroy', $row->id).'" method="POST" style="display:inline">
                                '.csrf_field().'
                                '.method_field('DELETE').'
                                <button type="submit" class="delete btn btn-danger btn-sm" onclick="return confirm(\'Are you sure?\')">Delete</button>
                            </form>';
                    return $btn;
                })
                ->editColumn('price', function($row){
                    return '₱' . number_format($row->price, 2);
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.products.index');
    }
 
    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        return view('admin.products.create', compact('categories', 'brands'));
    }
 
    public function store(StoreProductRequest $request)
    {
        $photos = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $photos[] = $photo->store('products', 'public');
            }
        }

        Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'photos' => $photos,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }
 
    public function edit(Product $product)
    {
        $categories = Category::all();
        $brands = Brand::all();
        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }
 
    public function update(UpdateProductRequest $request, Product $product)
    {
        $photos = $product->photos ?? [];
 
        if ($request->hasFile('replace_main_photo')) {
            $oldMain = $photos[0] ?? null;
            $newMain = $request->file('replace_main_photo')->store('products', 'public');
            
            if ($oldMain) { 
                $photos[0] = $newMain; 
                if (Storage::disk('public')->exists($oldMain) && !str_contains($oldMain, 'placeholder')) {
                    Storage::disk('public')->delete($oldMain);
                }
            } else {
                $photos[] = $newMain;
            }
        }
 
        if ($request->has('delete_photos')) {
            foreach ($request->delete_photos as $photoToDelete) {
                if (($key = array_search($photoToDelete, $photos)) !== false) {
                    unset($photos[$key]); 
                    if (Storage::disk('public')->exists($photoToDelete) && !str_contains($photoToDelete, 'placeholder')) {
                        Storage::disk('public')->delete($photoToDelete);
                    }
                }
            }
            $photos = array_values($photos); 
        }
 
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $photos[] = $photo->store('products', 'public');
            }
        }
 
        if ($request->has('main_photo')) {
            $mainPhoto = $request->main_photo;
            if (($key = array_search($mainPhoto, $photos)) !== false) { 
                unset($photos[$key]);
                array_unshift($photos, $mainPhoto);
            }
        }

        $product->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'photos' => $photos,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    } 

    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        Excel::import(new ProductsImport, $request->file('excel_file'));

        return redirect()->route('admin.products.index')->with('success', 'Products imported successfully.');
    }
 
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}
