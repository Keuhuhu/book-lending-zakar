@extends('layouts.app')
@section('title', 'Denda Saya')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    <h1 class="text-2xl font-bold mb-8 dark:text-white"><i class="fas fa-exclamation-circle mr-2 text-red-500"></i>Denda Saya</h1>

    @if($fines->isEmpty())
        <div class="text-center py-20 text-gray-400 bg-white dark:bg-gray-800 rounded-2xl shadow">
            <i class="fas fa-check-circle text-5xl mb-4 text-green-400"></i>
            <p class="text-xl">Tidak ada denda! Kamu tertib 🎉</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($fines as $fine)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-semibold dark:text-white">Peminjaman #{{ str_pad($fine->borrowing_id, 6, '0', STR_PAD_LEFT) }}</p>
                        <p class="text-sm text-gray-400 mt-1">Terlambat {{ $fine->days_late }} hari</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xl font-bold text-red-500">Rp {{ number_format($fine->amount, 0, ',', '.') }}</p>
                        <span class="text-xs px-3 py-1 rounded-full {{ $fine->status === 'paid' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                            {{ $fine->status === 'paid' ? 'Lunas' : 'Belum Dibayar' }}
                        </span>
                    </div>
                </div>
                @if($fine->status === 'unpaid')
                <form action="{{ route('fines.pay', $fine) }}" method="POST" class="mt-4">
                    @csrf
                    <button class="bg-red-500 text-white px-6 py-2 rounded-xl hover:bg-red-600 text-sm font-semibold">
                        <i class="fas fa-money-bill mr-2"></i>Bayar Denda
                    </button>
                </form>
                @endif
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection