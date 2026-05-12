<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\Fine;
use App\Models\FineSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class BorrowingController extends Controller
{
    public function index(Request $request)
    {
        $query = Borrowing::with('user', 'borrowingDetails.book');

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $borrowings = $query->latest()->paginate(15);
        return view('admin.borrowings.index', compact('borrowings'));
    }

    public function show(Borrowing $borrowing)
    {
        $borrowing->load('user', 'borrowingDetails.book', 'fine');
        return view('admin.borrowings.show', compact('borrowing'));
    }

    public function approve(Borrowing $borrowing)
    {
        // Buat folder qrcodes di public jika belum ada
        $qrDir = public_path('qrcodes');
        if (!file_exists($qrDir)) {
            mkdir($qrDir, 0755, true);
        }

        // Generate QR Code sebagai PNG
        $qrFilename = 'qr-' . $borrowing->id . '.svg'; 
        $qrPath     = $qrDir . '/' . $qrFilename;

        QrCode::format('svg')
            ->size(300)
            ->margin(2)
            ->generate(
                route('admin.borrowings.show', $borrowing),
                $qrPath
        );

        $borrowing->update([
            'status'  => 'approved',
            'qr_code' => 'qrcodes/' . $qrFilename,
        ]);

        return back()->with('success', 'Peminjaman disetujui dan QR Code telah dibuat.');
    }

    public function reject(Borrowing $borrowing)
    {
        foreach ($borrowing->borrowingDetails as $detail) {
            $detail->book->increment('stock');
        }

        $borrowing->update(['status' => 'rejected']);
        return back()->with('success', 'Peminjaman ditolak.');
    }

    public function confirmReturn(Borrowing $borrowing)
    {
        $today    = Carbon::today();
        $dueDate  = Carbon::parse($borrowing->due_date);
        $daysLate = max(0, $today->diffInDays($dueDate, false) * -1);

        $borrowing->update([
            'status'      => 'returned',
            'return_date' => $today,
        ]);

        foreach ($borrowing->borrowingDetails as $detail) {
            $detail->book->increment('stock');
        }

        if ($daysLate > 0) {
            $dailyFine = (int) FineSetting::get('daily_fine_amount', 2000);
            $amount    = $daysLate * $dailyFine;

            Fine::updateOrCreate(
                ['borrowing_id' => $borrowing->id],
                [
                    'user_id'   => $borrowing->user_id,
                    'amount'    => $amount,
                    'days_late' => $daysLate,
                    'status'    => 'unpaid',
                ]
            );

            $borrowing->update(['total_fine' => $amount]);
            $borrowing->user->update(['is_locked' => true]);
        }

        return back()->with('success', 'Pengembalian dikonfirmasi.' . ($daysLate > 0 ? " Denda Rp " . number_format($daysLate * 2000, 0, ',', '.') . " telah dicatat." : ''));
    }

    // ✅ Download E-Receipt PDF
    public function downloadReceipt(Borrowing $borrowing)
    {
        $borrowing->load('user', 'borrowingDetails.book', 'fine');

        $pdf = Pdf::loadView('pdf.borrowing-receipt', compact('borrowing'))
                  ->setPaper('a5', 'portrait');

        return $pdf->download('receipt-' . str_pad($borrowing->id, 6, '0', STR_PAD_LEFT) . '.pdf');
    }
}