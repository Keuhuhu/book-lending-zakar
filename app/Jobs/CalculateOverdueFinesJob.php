<?php

namespace App\Jobs;

use App\Models\Borrowing;
use App\Models\Fine;
use App\Models\FineSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class CalculateOverdueFinesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $today      = Carbon::today();
        $dailyFine  = (int) FineSetting::get('daily_fine_amount', 2000);

        // Ambil semua peminjaman 'borrowed' yang sudah melewati due_date
        $overdueBorrowings = Borrowing::with('user')
            ->where('status', 'borrowed')
            ->whereDate('due_date', '<', $today)
            ->get();

        foreach ($overdueBorrowings as $borrowing) {
            $dueDate  = Carbon::parse($borrowing->due_date);
            $daysLate = $today->diffInDays($dueDate);
            $amount   = $daysLate * $dailyFine;

            // Update atau buat record fine
            Fine::updateOrCreate(
                ['borrowing_id' => $borrowing->id],
                [
                    'user_id'   => $borrowing->user_id,
                    'amount'    => $amount,
                    'days_late' => $daysLate,
                    'status'    => 'unpaid',
                ]
            );

            // Update total_fine di borrowing
            $borrowing->update(['total_fine' => $amount]);
        }
    }
}