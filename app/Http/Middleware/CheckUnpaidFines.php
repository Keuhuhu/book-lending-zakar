<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Fine;

class CheckUnpaidFines
{
    public function handle(Request $request, Closure $next): mixed
    {
        if (auth()->check()) {
            $hasUnpaid = Fine::where('user_id', auth()->id())
                ->where('status', 'unpaid')
                ->exists();

            if ($hasUnpaid) {
                return redirect()->route('fines.index')
                    ->with('error', 'Anda memiliki denda yang belum dibayar. Selesaikan terlebih dahulu sebelum meminjam buku.');
            }
        }

        return $next($request);
    }
}