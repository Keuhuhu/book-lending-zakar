<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fine;

class FineController extends Controller
{
    public function index()
    {
        $fines = Fine::with('user', 'borrowing')->latest()->paginate(15);
        return view('admin.fines.index', compact('fines'));
    }

    public function markPaid(Fine $fine)
    {
        $fine->update(['status' => 'paid', 'paid_at' => now()]);
        $fine->borrowing->update(['fine_paid' => true]);
        $fine->user->update(['is_locked' => false]);

        return back()->with('success', 'Denda ditandai lunas.');
    }
}