<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Product') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('admin.products.update', $product->id) }}" enctype="multipart/form-data" novalidate>
                    @csrf
                    @method('PATCH')
 
                    <div class="mb-4">
                        <x-input-label for="name" :value="__('Product Name')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $product->name)" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
 
                    <div class="mb-4">
                        <x-input-label for="description" :value="__('Description')" />
                        <textarea id="description" name="description" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="4">{{ old('description', $product->description) }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4"> 
                        <div class="mb-4">
                            <x-input-label for="price" :value="__('Price (₱)')" />
                            <x-text-input id="price" class="block mt-1 w-full" type="number" step="0.01" name="price" :value="old('price', $product->price)" required />
                            <x-input-error :messages="$errors->get('price')" class="mt-2" />
                        </div>
 
                        <div class="mb-4">
                            <x-input-label for="stock" :value="__('Stock')" />
                            <x-text-input id="stock" class="block mt-1 w-full" type="number" name="stock" :value="old('stock', $product->stock)" required />
                            <x-input-error :messages="$errors->get('stock')" class="mt-2" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4"> 
                        <div class="mb-4">
                            <x-input-label for="category_id" :value="__('Category')" />
                            <select id="category_id" name="category_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                        </div>
 
                        <div class="mb-4">
                            <x-input-label for="brand_id" :value="__('Brand')" />
                            <select id="brand_id" name="brand_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Select Brand</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('brand_id')" class="mt-2" />
                        </div>
                    </div>
 
                    @if($product->photos && count($product->photos) > 0)
                        <div class="mb-6">
                            <x-input-label :value="__('Current Photos')" />
                            <p class="text-xs text-gray-500 mb-3 italic">Manage your product gallery below. The first photo is your main display.</p>
                            
                            <div class="flex flex-wrap gap-3 mt-2">
                                @foreach($product->photos as $index => $photo)
                                    <div class="relative flex flex-col border rounded overflow-hidden bg-white shadow-sm hover:shadow transition duration-200 w-24 h-auto" id="photo-card-{{ $index }}">
                                        <div class="h-16 bg-gray-100 overflow-hidden relative">
                                            <img src="{{ asset('storage/' . $photo) }}" alt="Product Photo" class="w-full h-full object-cover">
                                            
                                            @if($index === 0)
                                                <div class="absolute top-0 left-0 bg-indigo-600 text-white text-[8px] font-black px-1 py-0.5 rounded-br shadow-sm uppercase tracking-tighter z-10">Main</div>
                                            @endif
                                        </div>
 
                                        <div class="p-1 bg-gray-50 border-t flex justify-between items-center gap-1">
                                            @if($index === 0) 
                                                <label class="flex items-center justify-center flex-1 cursor-pointer bg-white border border-indigo-200 text-indigo-600 hover:bg-indigo-50 py-0.5 rounded transition shadow-sm" title="Change Main Photo">
                                                    <input type="file" name="replace_main_photo" class="hidden" onchange="this.closest('label').querySelector('span').innerText = '...'; this.closest('label').classList.add('bg-indigo-50');">
                                                    <svg class="w-2.5 h-2.5 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                                    <span class="text-[7px] font-bold uppercase">Change</span>
                                                </label>
                                            @else 
                                                <label class="flex items-center justify-center flex-1 cursor-pointer bg-white border border-red-200 text-red-600 hover:bg-red-50 py-0.5 rounded transition shadow-sm" title="Delete Photo">
                                                    <input type="checkbox" name="delete_photos[]" value="{{ $photo }}" class="hidden peer" onchange="this.closest('label').classList.toggle('bg-red-600'); this.closest('label').classList.toggle('text-white'); this.closest('label').classList.toggle('border-red-600');">
                                                    <svg class="w-2.5 h-2.5 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    <span class="text-[7px] font-bold uppercase">Del</span>
                                                </label>
 
                                                <label class="flex items-center justify-center flex-1 cursor-pointer bg-white border border-indigo-200 text-indigo-600 hover:bg-indigo-50 py-0.5 rounded transition shadow-sm" title="Set as Main">
                                                    <input type="radio" name="main_photo" value="{{ $photo }}" class="hidden peer" onchange="document.querySelectorAll('.set-main-text').forEach(el => el.innerText = 'Set'); this.closest('label').querySelector('.set-main-text').innerText = 'OK';">
                                                    <svg class="w-2.5 h-2.5 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                    <span class="text-[7px] font-bold uppercase set-main-text">Set</span>
                                                </label>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
 
                    <div class="mb-4">
                        <x-input-label for="photos" :value="__('Add Photos')" />
                        <input id="photos" type="file" name="photos[]" multiple class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                        <x-input-error :messages="$errors->get('photos')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <a href="{{ route('admin.products.index') }}" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-4">
                            {{ __('Cancel') }}
                        </a>
                        <x-primary-button>
                            {{ __('Update Product') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
