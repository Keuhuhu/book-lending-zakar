@extends('layouts.admin')
@section('title', 'Detail Peminjaman')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-8">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h2 class="text-xl font-bold dark:text-white">Detail Peminjaman #{{ str_pad($borrowing->id, 6, '0', STR_PAD_LEFT) }}</h2>
                <p class="text-gray-400 text-sm mt-1">{{ $borrowing->created_at->format('d M Y, H:i') }}</p>
            </div>
            @php $colors = ['pending'=>'bg-yellow-100 text-yellow-700','approved'=>'bg-blue-100 text-blue-700','borrowed'=>'bg-indigo-100 text-indigo-700','returned'=>'bg-green-100 text-green-700','rejected'=>'bg-red-100 text-red-700']; @endphp
            <span class="px-4 py-2 rounded-full text-sm font-semibold {{ $colors[$borrowing->status] ?? 'bg-gray-100' }}">
                {{ ucfirst($borrowing->status) }}
            </span>
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <h3 class="font-semibold mb-3 dark:text-white">Info Peminjam</h3>
                <div class="space-y-2 text-sm">
                    <p><span class="text-gray-400">Nama:</span> <span class="font-medium dark:text-gray-200">{{ $borrowing->user->name }}</span></p>
                    <p><span class="text-gray-400">Email:</span> <span class="font-medium dark:text-gray-200">{{ $borrowing->user->email }}</span></p>
                    <p><span class="text-gray-400">Tgl Pinjam:</span> <span class="font-medium dark:text-gray-200">{{ $borrowing->borrow_date->format('d M Y') }}</span></p>
                    <p><span class="text-gray-400">Batas Kembali:</span> <span class="font-medium dark:text-gray-200">{{ $borrowing->due_date->format('d M Y') }}</span></p>
                    @if($borrowing->notes)
                        <p><span class="text-gray-400">Catatan:</span> <span class="dark:text-gray-200">{{ $borrowing->notes }}</span></p>
                    @endif
                </div>

                <h3 class="font-semibold mt-6 mb-3 dark:text-white">Buku Dipinjam</h3>
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

            <div class="flex flex-col items-center justify-start">
                @if($borrowing->qr_code)
                    <div class="bg-white p-4 rounded-2xl shadow-md">
                        <img src="{{ asset($borrowing->qr_code) }}" class="w-48 h-48">
                    </div>
                    <p class="text-xs text-gray-400 mt-2 text-center">QR Code Peminjaman</p>
                @endif

                @if($borrowing->fine)
                <div class="mt-6 w-full bg-red-50 dark:bg-red-900 rounded-xl p-4">
                    <p class="text-sm font-semibold text-red-600 dark:text-red-300">Denda</p>
                    <p class="text-2xl font-bold text-red-500 mt-1">Rp {{ number_format($borrowing->fine->amount, 0, ',', '.') }}</p>
                    <p class="text-xs text-red-400">{{ $borrowing->fine->days_late }} hari terlambat</p>
                    <span class="text-xs px-2 py-1 rounded-full mt-2 inline-block {{ $borrowing->fine->status === 'paid' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                        {{ $borrowing->fine->status === 'paid' ? 'Lunas' : 'Belum Dibayar' }}
                    </span>
                </div>
                @endif
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex flex-wrap gap-3 mt-8 pt-6 border-t dark:border-gray-700">
            @if($borrowing->status === 'pending')
                <form action="{{ route('admin.borrowings.approve', $borrowing) }}" method="POST">
                    @csrf @method('PATCH')
                    <button class="bg-green-500 text-white px-5 py-2.5 rounded-xl hover:bg-green-600 font-semibold text-sm">
                        <i class="fas fa-check mr-2"></i>Setujui
                    </button>
                </form>
                <form action="{{ route('admin.borrowings.reject', $borrowing) }}" method="POST">
                    @csrf @method('PATCH')
                    <button class="bg-red-500 text-white px-5 py-2.5 rounded-xl hover:bg-red-600 font-semibold text-sm">
                        <i class="fas fa-times mr-2"></i>Tolak
                    </button>
                </form>
            @endif
            @if($borrowing->status === 'borrowed')
                <form action="{{ route('admin.borrowings.return', $borrowing) }}" method="POST" onsubmit="return confirm('Konfirmasi pengembalian buku?')">
                    @csrf @method('PATCH')
                    <button class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl hover:bg-indigo-700 font-semibold text-sm">
                        <i class="fas fa-undo mr-2"></i>Konfirmasi Kembali
                    </button>
                </form>
            @endif

            <a href="{{ route('admin.borrowings.receipt', $borrowing) }}" class="bg-purple-600 text-white px-5 py-2.5 rounded-xl hover:bg-purple-700 font-semibold text-sm flex items-center">
                <i class="fas fa-file-pdf mr-2"></i>Download Receipt
            </a>

            <a href="{{ route('admin.borrowings.index') }}" class="border dark:border-gray-600 text-gray-600 dark:text-gray-300 px-5 py-2.5 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 text-sm flex items-center">
                ← Kembali
            </a>
        </div>
    </div>
</div>
@endsection