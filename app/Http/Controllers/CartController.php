<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Book;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $carts = Cart::with('book')->where('user_id', auth()->id())->get();
        return view('cart.index', compact('carts'));
    }

    public function add(Book $book)
    {
        if ($book->stock <= 0) {
            return back()->with('error', 'Stok buku tidak tersedia.');
        }

        $exists = Cart::where('user_id', auth()->id())
            ->where('book_id', $book->id)->exists();

        if ($exists) {
            return back()->with('error', 'Buku sudah ada di keranjang.');
        }

        $cartCount = Cart::where('user_id', auth()->id())->count();
        if ($cartCount >= 3) {
            return back()->with('error', 'Maksimal 3 buku per peminjaman.');
        }

        Cart::create(['user_id' => auth()->id(), 'book_id' => $book->id]);
        return back()->with('success', 'Buku berhasil ditambahkan ke keranjang.');
    }

    public function remove(Cart $cart)
    {
        $cart->delete();
        return back()->with('success', 'Buku dihapus dari keranjang.');
    }
}