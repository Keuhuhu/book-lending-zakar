@extends('layouts.admin')
@section('title', 'Laporan')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6 mb-6">
    <form action="{{ route('admin.reports.index') }}" method="GET" class="flex gap-3 flex-wrap">
        <input type="date" name="from" value="{{ request('from') }}"
            class="border dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl px-4 py-2 text-sm outline-none">
        <input type="date" name="to" value="{{ request('to') }}"
            class="border dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl px-4 py-2 text-sm outline-none">
        <select name="status" class="border dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl px-4 py-2 text-sm outline-none">
            <option value="">Semua Status</option>
            @foreach(['pending','approved','borrowed','returned','rejected'] as $s)
                <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <button class="bg-indigo-600 text-white px-5 py-2 rounded-xl text-sm hover:bg-indigo-700">Filter</button>
    </form>
</div>

<div class="bg-white dark:bg-gray-800 rounded-2xl shadow overflow-hidden">
    <div class="px-6 py-4 border-b dark:border-gray-700 flex justify-between items-center flex-wrap gap-4">
        <p class="font-semibold dark:text-white">{{ $borrowings->count() }} data ditemukan</p>
        
        <div class="flex gap-2">
            <a href="{{ route('admin.reports.export-pdf', request()->query()) }}" 
               class="bg-red-500 text-white px-5 py-2 rounded-xl text-sm hover:bg-red-600 flex items-center">
                <i class="fas fa-file-pdf mr-1"></i>Export PDF
            </a>
            <a href="{{ route('admin.reports.export-excel', request()->query()) }}" 
               class="bg-green-500 text-white px-5 py-2 rounded-xl text-sm hover:bg-green-600 flex items-center">
                <i class="fas fa-file-excel mr-1"></i>Export Excel
            </a>
        </div>
    </div>
    <table class="w-full text-sm">
        <thead class="bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-300">
            <tr>
                <th class="px-6 py-4 text-left">Peminjam</th>
                <th class="px-6 py-4 text-left">Buku</th>
                <th class="px-6 py-4 text-left">Tgl Pinjam</th>
                <th class="px-6 py-4 text-left">Status</th>
                <th class="px-6 py-4 text-left">Denda</th>
            </tr>
        </thead>
        <tbody class="divide-y dark:divide-gray-700">
            @forelse($borrowings as $b)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                <td class="px-6 py-4 font-medium dark:text-white">{{ $b->user->name }}</td>
                <td class="px-6 py-4">
                    @foreach($b->borrowingDetails as $d)
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $d->book->title }}</p>
                    @endforeach
                </td>
                <td class="px-6 py-4 text-gray-500 dark:text-gray-400">{{ $b->borrow_date->format('d/m/Y') }}</td>
                <td class="px-6 py-4">
                    @php $colors = ['pending'=>'bg-yellow-100 text-yellow-700','approved'=>'bg-blue-100 text-blue-700','borrowed'=>'bg-indigo-100 text-indigo-700','returned'=>'bg-green-100 text-green-700','rejected'=>'bg-red-100 text-red-700']; @endphp
                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $colors[$b->status] ?? 'bg-gray-100' }}">{{ ucfirst($b->status) }}</span>
                </td>
                <td class="px-6 py-4 {{ $b->total_fine > 0 ? 'text-red-500 font-semibold' : 'text-gray-400' }}">
                    {{ $b->total_fine > 0 ? 'Rp '.number_format($b->total_fine, 0, ',', '.') : '-' }}
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-6 py-12 text-center text-gray-400">Tidak ada data</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection