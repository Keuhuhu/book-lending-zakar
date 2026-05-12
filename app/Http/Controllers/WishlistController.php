<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Book;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Wishlist::with('book')->where('user_id', auth()->id())->get();
        return view('wishlist.index', compact('wishlists'));
    }

    public function toggle(Book $book)
    {
        $wishlist = Wishlist::where('user_id', auth()->id())
            ->where('book_id', $book->id)->first();

        if ($wishlist) {
            $wishlist->delete();
            return back()->with('success', 'Dihapus dari wishlist.');
        }

        Wishlist::create(['user_id' => auth()->id(), 'book_id' => $book->id]);
        return back()->with('success', 'Ditambahkan ke wishlist.');
    }
}