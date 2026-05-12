<?php

namespace App\Jobs;

use App\Models\Borrowing;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendReturnReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        // Ambil semua peminjaman yang due_date-nya besok & status 'borrowed'
        $tomorrow = Carbon::tomorrow()->toDateString();

        $borrowings = Borrowing::with('user', 'borrowingDetails.book')
            ->where('status', 'borrowed')
            ->whereDate('due_date', $tomorrow)
            ->get();

        foreach ($borrowings as $borrowing) {
            Mail::send('emails.return-reminder', [
                'borrowing' => $borrowing,
                'user'      => $borrowing->user,
            ], function ($message) use ($borrowing) {
                $message->to($borrowing->user->email, $borrowing->user->name)
                        ->subject('⏰ Pengingat: Pengembalian Buku Besok!');
            });
        }
    }
}