<?php

namespace App\Jobs;

use App\Models\Borrowing;
use App\Models\FineSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class AutoCancelExpiredBorrowingsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        // Ambil setting jam batas auto cancel (default 24 jam)
        $limitHours = (int) FineSetting::get('auto_cancel_hours', 24);

        // Cari peminjaman 'approved' yang sudah melebihi batas jam
        $expiredBorrowings = Borrowing::with('borrowingDetails.book')
            ->where('status', 'approved')
            ->where('updated_at', '<=', Carbon::now()->subHours($limitHours))
            ->get();

        foreach ($expiredBorrowings as $borrowing) {
            // Kembalikan stok buku
            foreach ($borrowing->borrowingDetails as $detail) {
                $detail->book->increment('stock');
            }

            // Update status jadi expired
            $borrowing->update(['status' => 'expired']);
        }
    }
}