@extends('layouts.app')
@section('title', 'Detail Peminjaman #'.$borrowing->id)

@section('content')
<div class="max-w-3xl mx-auto px-4 py-10">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden">
        {{-- Header --}}
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold">E-Receipt Peminjaman</h1>
                    <p class="text-indigo-200 text-sm mt-1">#{{ str_pad($borrowing->id, 6, '0', STR_PAD_LEFT) }}</p>
                </div>
                @php
                    $colors = ['pending'=>'bg-yellow-400','approved'=>'bg-blue-400','borrowed'=>'bg-indigo-400','returned'=>'bg-green-400','rejected'=>'bg-red-400','expired'=>'bg-gray-400'];
                @endphp
                <span class="px-4 py-2 rounded-full text-sm font-bold {{ $colors[$borrowing->status] ?? 'bg-gray-400' }} text-white">
                    {{ strtoupper($borrowing->status) }}
                </span>
            </div>
        </div>

        <div class="p-6 grid md:grid-cols-2 gap-6">
            {{-- Info --}}
            <div>
                <h2 class="font-bold text-lg mb-4 dark:text-white">Informasi Peminjaman</h2>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between border-b dark:border-gray-700 pb-2">
                        <span class="text-gray-400">Peminjam</span>
                        <span class="font-medium dark:text-gray-200">{{ $borrowing->user->name }}</span>
                    </div>
                    <div class="flex justify-between border-b dark:border-gray-700 pb-2">
                        <span class="text-gray-400">Tanggal Pinjam</span>
                        <span class="font-medium dark:text-gray-200">{{ $borrowing->borrow_date->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between border-b dark:border-gray-700 pb-2">
                        <span class="text-gray-400">Batas Kembali</span>
                        <span class="font-medium dark:text-gray-200">{{ $borrowing->due_date->format('d M Y') }}</span>
                    </div>
                    @if($borrowing->return_date)
                    <div class="flex justify-between border-b dark:border-gray-700 pb-2">
                        <span class="text-gray-400">Tgl Kembali</span>
                        <span class="font-medium text-green-500">{{ $borrowing->return_date->format('d M Y') }}</span>
                    </div>
                    @endif
                    @if($borrowing->total_fine > 0)
                    <div class="flex justify-between">
                        <span class="text-gray-400">Total Denda</span>
                        <span class="font-bold text-red-500">Rp {{ number_format($borrowing->total_fine, 0, ',', '.') }}</span>
                    </div>
                    @endif
                </div>

                {{-- Buku --}}
                <h2 class="font-bold text-lg mt-6 mb-3 dark:text-white">Buku Dipinjam</h2>
                <div class="space-y-2">
                    @foreach($borrowing->borrowingDetails as $detail)
                    <div class="flex items-center gap-3 bg-gray-50 dark:bg-gray-700 rounded-xl p-3">
                        <i class="fas fa-book text-indigo-400"></i>
                        <div>
                            <p class="text-sm font-medium dark:text-gray-200">{{ $detail->book->title }}</p>
                            <p class="text-xs text-gray-400">{{ $detail->book->author }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- QR Code --}}
            <div class="flex flex-col items-center justify-center">
                @if($borrowing->qr_code)
                    <div class="bg-white p-4 rounded-2xl shadow-md">
                        <img src="{{ asset($borrowing->qr_code) }}" alt="QR Code" class="w-48 h-48">
                    </div>
                    <p class="text-sm text-gray-400 mt-3 text-center">Tunjukkan QR Code ini saat mengambil buku</p>
                @else
                    <div class="text-center text-gray-400">
                        <i class="fas fa-qrcode text-6xl mb-3"></i>
                        <p class="text-sm">QR Code akan muncul setelah peminjaman disetujui admin</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="p-6 pt-0">
            <a href="{{ route('borrowings.index') }}" class="text-indigo-600 hover:underline text-sm">
                <i class="fas fa-arrow-left mr-1"></i>Kembali ke Riwayat
            </a>
        </div>
    </div>
</div>
@endsection