<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StockLog;

class StockLogController extends Controller
{
    public function index()
    {
        $logs = StockLog::with('book', 'admin')->latest()->paginate(20);
        return view('admin.stock-logs.index', compact('logs'));
    }
}