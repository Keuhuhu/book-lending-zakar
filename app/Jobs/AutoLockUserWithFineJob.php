<?php

namespace App\Jobs;

use App\Models\Fine;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AutoLockUserWithFineJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        // Kumpulkan user_id yang punya denda belum bayar
        $userIdsWithUnpaidFine = Fine::where('status', 'unpaid')
            ->pluck('user_id')
            ->unique();

        // Lock semua user tersebut
        User::whereIn('id', $userIdsWithUnpaidFine)
            ->update(['is_locked' => true]);

        // Unlock user yang sudah tidak punya denda unpaid
        User::whereNotIn('id', $userIdsWithUnpaidFine)
            ->where('is_locked', true)
            ->update(['is_locked' => false]);
    }
}