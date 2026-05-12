@extends('layouts.app')
@section('title', 'Riwayat Peminjaman')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-10">
    <h1 class="text-2xl font-bold mb-8 dark:text-white"><i class="fas fa-history mr-2 text-indigo-600"></i>Riwayat Peminjaman</h1>

    @if($borrowings->isEmpty())
        <div class="text-center py-20 text-gray-400 bg-white dark:bg-gray-800 rounded-2xl shadow">
            <i class="fas fa-book text-5xl mb-4"></i>
            <p class="text-xl mb-4">Belum ada riwayat peminjaman</p>
            <a href="{{ route('catalog.index') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-xl hover:bg-indigo-700">Mulai Meminjam</a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($borrowings as $borrowing)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <p class="text-xs text-gray-400">ID Peminjaman #{{ $borrowing->id }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            <i class="fas fa-calendar mr-1"></i>
                            {{ $borrowing->borrow_date->format('d M Y') }} →
                            {{ $borrowing->due_date->format('d M Y') }}
                        </p>
                    </div>
                    @php
                        $colors = [
                            'pending' => 'bg-yellow-100 text-yellow-700',
                            'approved' => 'bg-blue-100 text-blue-700',
                            'borrowed' => 'bg-indigo-100 text-indigo-700',
                            'returned' => 'bg-green-100 text-green-700',
                            'rejected' => 'bg-red-100 text-red-700',
                            'expired' => 'bg-gray-100 text-gray-700',
                        ];
                    @endphp
                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $colors[$borrowing->status] ?? 'bg-gray-100 text-gray-700' }}">
                        {{ ucfirst($borrowing->status) }}
                    </span>
                </div>

                <div class="flex flex-wrap gap-2 mb-4">
                    @foreach($borrowing->borrowingDetails as $detail)
                        <span class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs px-3 py-1 rounded-full">
                            <i class="fas fa-book mr-1"></i>{{ $detail->book->title }}
                        </span>
                    @endforeach
                </div>

                <div class="flex items-center justify-between">
                    @if($borrowing->total_fine > 0)
                        <span class="text-sm text-red-500 font-semibold">
                            <i class="fas fa-exclamation-circle mr-1"></i>Denda: Rp {{ number_format($borrowing->total_fine, 0, ',', '.') }}
                        </span>
                    @else
                        <span></span>
                    @endif
                    <a href="{{ route('borrowings.show', $borrowing) }}" class="text-sm text-indigo-600 hover:underline font-medium">
                        Detail <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-6">{{ $borrowings->links() }}</div>
    @endif
</div>
@endsection