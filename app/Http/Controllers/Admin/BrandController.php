<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Http\Requests\Admin\StoreBrandRequest;
use App\Http\Requests\Admin\UpdateBrandRequest;
use Illuminate\Support\Str;

class BrandController extends Controller
{ 
    public function index()
    {
        $brands = Brand::latest()->get();
        return view('admin.brands.index', compact('brands'));
    }
 
    public function create()
    {
        return view('admin.brands.create');
    }
 
    public function store(StoreBrandRequest $request)
    {
        Brand::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('admin.brands.index')->with('success', 'Brand created successfully.');
    }
 
    public function edit(Brand $brand)
    {
        return view('admin.brands.edit', compact('brand'));
    }
 
    public function update(UpdateBrandRequest $request, Brand $brand)
    {
        $brand->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('admin.brands.index')->with('success', 'Brand updated successfully.');
    }
 
    public function destroy(Brand $brand)
    {
        if ($brand->products()->count() > 0) {
            return redirect()->route('admin.brands.index')->with('error', 'Brand cannot be deleted as it has associated products.');
        }

        $brand->delete();

        return redirect()->route('admin.brands.index')->with('success', 'Brand deleted successfully.');
    }
}
