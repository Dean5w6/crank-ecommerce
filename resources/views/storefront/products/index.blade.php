<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Product Catalog') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row gap-8"> 
                <div class="w-full md:w-1/4">
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <h3 class="font-bold text-lg mb-4 border-b pb-2">Filters</h3>
                        
                        <form action="{{ route('products.index') }}" method="GET" novalidate> 
                            <div class="mb-6">
                                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search Products</label>
                                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search..." class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
 
                            <div class="mb-6">
                                <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                                <select name="category" id="category" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
 
                            <div class="mb-6">
                                <label for="brand" class="block text-sm font-medium text-gray-700 mb-2">Brand</label>
                                <select name="brand" id="brand" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">All Brands</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->slug }}" {{ request('brand') == $brand->slug ? 'selected' : '' }}>{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>
 
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Price Range (₱)</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                    <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                </div>
                            </div>

                            <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-2 px-4 rounded hover:bg-indigo-700 transition">
                                Apply Filters
                            </button>
                            <a href="{{ route('products.index') }}" class="block text-center mt-2 text-sm text-gray-600 hover:underline">Clear Filters</a>
                        </form>
                    </div>
                </div>
 
                <div class="w-full md:w-3/4">
                    @if($products->isEmpty())
                        <div class="bg-white p-12 text-center rounded-lg shadow-sm">
                            <h3 class="text-xl text-gray-500 italic">No products found matching your criteria.</h3>
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($products as $product)
                                <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition group">
                                    <a href="{{ route('products.show', $product->slug) }}">
                                        <div class="h-48 overflow-hidden bg-gray-200">
                                            @php
                                                $mainPhoto = (is_array($product->photos) && count($product->photos) > 0) ? $product->photos[0] : 'products/product_placeholder.png';
                                                $photoUrl = asset('storage/' . $mainPhoto);
                                            @endphp
                                            <img src="{{ $photoUrl }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                        </div>
                                        <div class="p-4">
                                            <div class="text-xs font-semibold text-indigo-600 mb-1 uppercase tracking-wider">{{ $product->category->name }}</div>
                                            <h3 class="font-bold text-gray-900 mb-1 truncate">{{ $product->name }}</h3>
                                            <p class="text-gray-500 text-xs mb-3 truncate">{{ $product->brand->name }}</p>
                                            <div class="flex justify-between items-center">
                                                <span class="text-lg font-bold text-gray-900">₱{{ number_format($product->price, 2) }}</span>
                                                <span class="text-xs px-2 py-1 rounded-full {{ $product->stock > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $product->stock > 0 ? 'In Stock' : 'Out of Stock' }}
                                                </span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-8">
                            {{ $products->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
