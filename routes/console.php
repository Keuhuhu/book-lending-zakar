<?php

use App\Jobs\AutoCancelExpiredBorrowingsJob;
use App\Jobs\AutoLockUserWithFineJob;
use App\Jobs\CalculateOverdueFinesJob;
use App\Jobs\SendReturnReminderJob;
use Illuminate\Support\Facades\Schedule;

// ✅ Kirim email reminder H-1 sebelum due_date — tiap hari jam 08:00
Schedule::job(new SendReturnReminderJob)
    ->dailyAt('08:00')
    ->name('send-return-reminder')
    ->withoutOverlapping();

// ✅ Auto cancel peminjaman 'approved' yang melebihi batas jam — tiap jam
Schedule::job(new AutoCancelExpiredBorrowingsJob)
    ->hourly()
    ->name('auto-cancel-expired-borrowings')
    ->withoutOverlapping();

// ✅ Hitung denda otomatis untuk peminjaman terlambat — tiap tengah malam
Schedule::job(new CalculateOverdueFinesJob)
    ->dailyAt('00:00')
    ->name('calculate-overdue-fines')
    ->withoutOverlapping();

// ✅ Lock/unlock user berdasarkan status denda — tiap jam
Schedule::job(new AutoLockUserWithFineJob)
    ->hourly()
    ->name('auto-lock-user-with-fine')
    ->withoutOverlapping();