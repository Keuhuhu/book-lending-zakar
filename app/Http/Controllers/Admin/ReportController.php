<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BorrowingsExport;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Borrowing::with('user', 'borrowingDetails.book');

        if ($request->from) {
            $query->whereDate('created_at', '>=', $request->from);
        }
        if ($request->to) {
            $query->whereDate('created_at', '<=', $request->to);
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $borrowings = $query->latest()->get();

        return view('admin.reports.index', compact('borrowings'));
    }

    public function exportPdf(Request $request)
    {
        $query = Borrowing::with('user', 'borrowingDetails.book');

        if ($request->from) $query->whereDate('created_at', '>=', $request->from);
        if ($request->to)   $query->whereDate('created_at', '<=', $request->to);
        if ($request->status) $query->where('status', $request->status);

        $borrowings = $query->latest()->get();

        $pdf = Pdf::loadView('pdf.report', compact('borrowings'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download('laporan-peminjaman-' . now()->format('Y-m-d') . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(
            new BorrowingsExport($request->all()),
            'laporan-peminjaman-' . now()->format('Y-m-d') . '.xlsx'
        );
    }
}