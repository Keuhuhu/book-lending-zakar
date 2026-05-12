<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Book;
use App\Models\Borrowing;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Book $book)
    {
        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        $hasBorrowed = Borrowing::where('user_id', auth()->id())
            ->where('status', 'returned')
            ->whereHas('books', fn($q) => $q->where('book_id', $book->id))
            ->exists();

        if (!$hasBorrowed) {
            return back()->with('error', 'Anda hanya bisa mereview buku yang sudah dikembalikan.');
        }

        Review::updateOrCreate(
            ['user_id' => auth()->id(), 'book_id' => $book->id],
            ['rating' => $request->rating, 'comment' => $request->comment]
        );

        return back()->with('success', 'Review berhasil disimpan.');
    }
}