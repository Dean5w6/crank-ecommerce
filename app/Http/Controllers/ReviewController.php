<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{ 
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);
 
        $existingReview = Review::where('user_id', Auth::id())
                                ->where('product_id', $request->product_id)
                                ->first();

        if ($existingReview) {
            return back()->with('error', 'You have already reviewed this product.');
        }
 
        $canReview = Auth::user()->transactions()
            ->where('status', 'completed')
            ->whereHas('items', function ($query) use ($request) {
                $query->where('product_id', $request->product_id);
            })
            ->exists();

        if (!$canReview) {
            return back()->with('error', 'Only verified buyers can review this product.');
        }

        $comment = $this->filterProfanity($request->comment);

        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'comment' => $comment,
        ]);

        return back()->with('success', 'Thank you for your review!');
    }
 
    public function update(Request $request, Review $review)
    {
        if ($review->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        $comment = $this->filterProfanity($request->comment);

        $review->update([
            'rating' => $request->rating,
            'comment' => $comment,
        ]);

        return back()->with('success', 'Review updated successfully.');
    }
 
    public function destroy(Review $review)
    { 
        if ($review->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $review->delete();

        return back()->with('success', 'Review deleted.');
    }
 
    private function filterProfanity($text)
    {
        $profanities = [ 
            'fuck', 'shit', 'bitch', 'asshole', 'pussy', 'dick', 'cunt', 
            'putangina', 'gago', 'tarantado', 'tanga', 'bobo', 'pakshet', 'ulol', 'kantot', 'pekpek', 
            'nigger', 'nigga', 'faggot', 'fag', 'kike', 'chink'
        ];

        foreach ($profanities as $word) { 
            $replacement = str_repeat('*', strlen($word));
            $text = preg_replace('/\b' . preg_quote($word, '/') . '\b/i', '***', $text);
        }

        return $text;
    }
}
