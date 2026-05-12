@extends('layouts.admin')
@section('title', 'Log Stok Buku')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-2xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-300">
            <tr>
                <th class="px-6 py-4 text-left">Buku</th>
                <th class="px-6 py-4 text-left">Tipe</th>
                <th class="px-6 py-4 text-left">Jumlah</th>
                <th class="px-6 py-4 text-left">Alasan</th>
                <th class="px-6 py-4 text-left">Admin</th>
                <th class="px-6 py-4 text-left">Waktu</th>
            </tr>
        </thead>
        <tbody class="divide-y dark:divide-gray-700">
            @forelse($logs as $log)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                <td class="px-6 py-4 font-medium dark:text-white">{{ $log->book->title }}</td>
                <td class="px-6 py-4">
                    @php $typeColors = ['in'=>'bg-green-100 text-green-600','out'=>'bg-red-100 text-red-600','adjustment'=>'bg-yellow-100 text-yellow-600']; @endphp
                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $typeColors[$log->type] ?? 'bg-gray-100' }}">
                        {{ ucfirst($log->type) }}
                    </span>
                </td>
                <td class="px-6 py-4 font-semibold dark:text-white">{{ $log->quantity }}</td>
                <td class="px-6 py-4 text-gray-500 dark:text-gray-400">{{ $log->reason ?? '-' }}</td>
                <td class="px-6 py-4 text-gray-500 dark:text-gray-400">{{ $log->admin?->name ?? 'Sistem' }}</td>
                <td class="px-6 py-4 text-gray-400 text-xs">{{ $log->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400">Tidak ada log stok</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t dark:border-gray-700">{{ $logs->links() }}</div>
</div>
@endsection