@extends('layouts.admin')
@section('title', 'Manajemen Denda')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-2xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-300">
            <tr>
                <th class="px-6 py-4 text-left">Pengguna</th>
                <th class="px-6 py-4 text-left">Peminjaman</th>
                <th class="px-6 py-4 text-left">Jumlah</th>
                <th class="px-6 py-4 text-left">Terlambat</th>
                <th class="px-6 py-4 text-left">Status</th>
                <th class="px-6 py-4 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y dark:divide-gray-700">
            @forelse($fines as $fine)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                <td class="px-6 py-4">
                    <p class="font-medium dark:text-white">{{ $fine->user->name }}</p>
                    <p class="text-xs text-gray-400">{{ $fine->user->email }}</p>
                </td>
                <td class="px-6 py-4 text-gray-500 dark:text-gray-400">#{{ str_pad($fine->borrowing_id, 6, '0', STR_PAD_LEFT) }}</td>
                <td class="px-6 py-4 font-bold text-red-500">Rp {{ number_format($fine->amount, 0, ',', '.') }}</td>
                <td class="px-6 py-4 text-gray-500 dark:text-gray-400">{{ $fine->days_late }} hari</td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $fine->status === 'paid' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                        {{ $fine->status === 'paid' ? 'Lunas' : 'Belum Dibayar' }}
                    </span>
                </td>
                <td class="px-6 py-4 text-center">
                    @if($fine->status === 'unpaid')
                    <form action="{{ route('admin.fines.paid', $fine) }}" method="POST">
                        @csrf @method('PATCH')
                        <button class="text-xs px-3 py-1.5 bg-green-100 text-green-600 rounded-lg hover:bg-green-200 font-medium">
                            <i class="fas fa-check mr-1"></i>Tandai Lunas
                        </button>
                    </form>
                    @else
                        <span class="text-xs text-gray-400">{{ $fine->paid_at?->format('d/m/Y') }}</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-12 text-center text-gray-400">Tidak ada data denda</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t dark:border-gray-700">{{ $fines->links() }}</div>
</div>
@endsection