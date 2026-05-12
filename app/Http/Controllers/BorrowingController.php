<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Borrowing;
use App\Models\BorrowingDetail;
use App\Models\FineSetting;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BorrowingController extends Controller
{
    public function checkout()
    {
        $carts = Cart::with('book')->where('user_id', auth()->id())->get();

        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kosong.');
        }

        $maxDays = (int) FineSetting::get('max_borrow_days', 7);

        $borrowing = Borrowing::create([
            'user_id'     => auth()->id(),
            'borrow_date' => Carbon::today(),
            'due_date'    => Carbon::today()->addDays($maxDays),
            'status'      => 'pending',
        ]);

        foreach ($carts as $cart) {
            BorrowingDetail::create([
                'borrowing_id' => $borrowing->id,
                'book_id'      => $cart->book_id,
            ]);
            $cart->book->decrement('stock');
        }

        Cart::where('user_id', auth()->id())->delete();

        return redirect()->route('borrowings.show', $borrowing)
            ->with('success', 'Peminjaman berhasil diajukan! Menunggu persetujuan admin.');
    }

    public function myHistory()
    {
        $borrowings = Borrowing::with('borrowingDetails.book')
            ->where('user_id', auth()->id())
            ->latest()->paginate(10);

        return view('borrowings.index', compact('borrowings'));
    }

    public function show(Borrowing $borrowing)
    {
        $borrowing->load('borrowingDetails.book', 'fine');
        return view('borrowings.show', compact('borrowing'));
    }
}