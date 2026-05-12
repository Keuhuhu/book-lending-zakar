<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\User;
use App\Models\Fine;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBooks     = Book::count();
        $totalUsers     = User::where('role', 'user')->count();
        $activeBorrows  = Borrowing::whereIn('status', ['approved', 'borrowed'])->count();
        $unpaidFines    = Fine::where('status', 'unpaid')->sum('amount');

        $recentBorrowings = Borrowing::with('user', 'borrowingDetails.book')
            ->latest()->limit(5)->get();

        $monthlyData = Borrowing::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')->get();

        return view('admin.dashboard', compact(
            'totalBooks', 'totalUsers', 'activeBorrows',
            'unpaidFines', 'recentBorrowings', 'monthlyData'
        ));
    }
}