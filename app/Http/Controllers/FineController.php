<?php

namespace App\Http\Controllers;

use App\Models\Fine;

class FineController extends Controller
{
    public function index()
    {
        $fines = Fine::with('borrowing')
            ->where('user_id', auth()->id())
            ->latest()->get();

        return view('fines.index', compact('fines'));
    }

    public function pay(Fine $fine)
    {
        if ($fine->user_id !== auth()->id()) abort(403);

        $fine->update([
            'status'  => 'paid',
            'paid_at' => now(),
        ]);

        auth()->user()->update(['is_locked' => false]);

        return back()->with('success', 'Denda berhasil dibayar.');
    }
}