<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $product->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12"> 
                    <div>
                        @php
                            $hasPhotos = is_array($product->photos) && count($product->photos) > 0;
                            $mainPhoto = $hasPhotos ? $product->photos[0] : 'products/product_placeholder.png';
                            $mainPhotoUrl = asset('storage/' . $mainPhoto);
                        @endphp
                        <div class="mb-4">
                            <img src="{{ $mainPhotoUrl }}" alt="{{ $product->name }}" class="w-full rounded-lg shadow-sm border h-96 object-cover" id="mainImage">
                        </div>
                        
                        @if($hasPhotos && count($product->photos) > 1)
                            <div class="flex flex-wrap gap-2">
                                @foreach($product->photos as $photo)
                                    @php
                                        $thumbUrl = asset('storage/' . $photo);
                                    @endphp
                                    <img src="{{ $thumbUrl }}" alt="Product Photo" class="w-20 h-20 object-cover rounded shadow-sm border cursor-pointer hover:border-indigo-500 transition thumbnail" onclick="document.getElementById('mainImage').src = this.src">
                                @endforeach
                            </div>
                        @endif
                    </div>
 
                    <div>
                        <div class="mb-6">
                            <span class="text-xs font-bold text-indigo-600 uppercase tracking-widest border border-indigo-200 px-2 py-1 rounded bg-indigo-50">{{ $product->category->name }}</span>
                            <span class="ml-2 text-xs font-bold text-gray-500 uppercase tracking-widest border border-gray-200 px-2 py-1 rounded bg-gray-50">{{ $product->brand->name }}</span>
                        </div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $product->name }}</h1>
                        <p class="text-2xl font-bold text-green-600 mb-6">₱{{ number_format($product->price, 2) }}</p>
                        
                        <div class="mb-6">
                            <h3 class="font-semibold text-gray-700 mb-2 border-b pb-1">Description</h3>
                            <p class="text-gray-600 leading-relaxed">{{ $product->description }}</p>
                        </div>

                        <div class="mb-8">
                            <h3 class="font-semibold text-gray-700 mb-2 border-b pb-1">Availability</h3>
                            <p class="{{ $product->stock > 0 ? 'text-green-600' : 'text-red-600' }} font-bold">
                                {{ $product->stock > 0 ? $product->stock . ' units in stock' : 'Out of stock' }}
                            </p>
                        </div>

                        <div class="flex gap-4">
                            <form action="{{ route('cart.add') }}" method="POST" class="w-full">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <div class="flex items-center gap-4 mb-4">
                                    <label for="quantity" class="text-gray-700 font-semibold">Quantity:</label>
                                    <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $product->stock }}" class="w-20 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                                <button type="submit" class="bg-indigo-600 text-white font-bold py-3 px-8 rounded-lg hover:bg-indigo-700 transition w-full shadow-lg {{ $product->stock <= 0 ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                    Add to Cart
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
 
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-8">
                <div class="flex justify-between items-center mb-6 border-b pb-2">
                    <h3 class="text-xl font-bold text-gray-900">Customer Reviews</h3>
                    @if($canReview)
                        <button onclick="document.getElementById('reviewForm').classList.toggle('hidden')" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-semibold hover:bg-indigo-700 transition">
                            Write a Review
                        </button>
                    @endif
                </div>

                @if($canReview)
                    <div id="reviewForm" class="hidden mb-8 bg-gray-50 p-6 rounded-lg border border-gray-200">
                        <h4 class="font-bold text-gray-800 mb-4">Your Review</h4>
                        <form action="{{ route('reviews.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Rating</label>
                                <select name="rating" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="5">5 Stars - Excellent</option>
                                    <option value="4">4 Stars - Very Good</option>
                                    <option value="3">3 Stars - Good</option>
                                    <option value="2">2 Stars - Fair</option>
                                    <option value="1">1 Star - Poor</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Comment</label>
                                <textarea name="comment" rows="4" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Share your experience with this product..."></textarea>
                            </div>

                            <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-2 px-4 rounded-md hover:bg-indigo-700 transition">
                                Submit Review
                            </button>
                        </form>
                    </div>
                @endif

                @if($product->reviews->isEmpty())
                    <p class="text-gray-500 italic py-4">No reviews yet for this product. Be the first to review!</p>
                @else
                    <div class="space-y-6">
                        @foreach($product->reviews as $review)
                            <div class="border-b border-gray-100 pb-4 last:border-0">
                                <div class="flex justify-between items-center mb-2">
                                    <div class="flex items-center gap-2">
                                        <img src="{{ $review->user->photo_url }}" alt="{{ $review->user->name }}" class="w-8 h-8 rounded-full border">
                                        <span class="font-semibold text-gray-800">{{ $review->user->name }}</span>
                                    </div>
                                    <div class="flex items-center text-yellow-400">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 {{ $i <= $review->rating ? 'fill-current' : 'text-gray-300' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                                        @endfor
                                    </div>
                                </div>
                                <p class="text-gray-600 text-sm leading-relaxed">{{ $review->comment }}</p>
                                <p class="text-xs text-gray-400 mt-2">{{ $review->created_at->format('M d, Y') }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
 
            @if($related_products->isNotEmpty())
                <h3 class="text-xl font-bold text-gray-900 mb-6 px-4">Related Products</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 px-4">
                    @foreach($related_products as $related)
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition group">
                            <a href="{{ route('products.show', $related->slug) }}">
                                <div class="h-40 overflow-hidden bg-gray-200">
                                    @php
                                        $relatedPhoto = (is_array($related->photos) && count($related->photos) > 0) ? $related->photos[0] : 'products/product_placeholder.png';
                                        $relatedPhotoUrl = asset('storage/' . $relatedPhoto);
                                    @endphp
                                    <img src="{{ $relatedPhotoUrl }}" alt="{{ $related->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                </div>
                                <div class="p-4">
                                    <h3 class="font-bold text-gray-900 mb-1 truncate text-sm">{{ $related->name }}</h3>
                                    <div class="flex justify-between items-center mt-2">
                                        <span class="text-sm font-bold text-gray-900">₱{{ number_format($related->price, 2) }}</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
