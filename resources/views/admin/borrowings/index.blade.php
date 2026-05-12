@extends('layouts.admin')
@section('title', 'Manajemen Peminjaman')

@section('content')
{{-- Filter --}}
<div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-4 mb-6">
    <form action="{{ route('admin.borrowings.index') }}" method="GET" class="flex gap-3 flex-wrap">
        <select name="status" class="border dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl px-4 py-2 text-sm outline-none">
            <option value="">Semua Status</option>
            @foreach(['pending','approved','borrowed','returned','rejected','expired'] as $s)
                <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <button class="bg-indigo-600 text-white px-5 py-2 rounded-xl text-sm hover:bg-indigo-700">Filter</button>
        <a href="{{ route('admin.borrowings.index') }}" class="border dark:border-gray-600 text-gray-600 dark:text-gray-300 px-5 py-2 rounded-xl text-sm hover:bg-gray-50 dark:hover:bg-gray-700">Reset</a>
    </form>
</div>

<div class="bg-white dark:bg-gray-800 rounded-2xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-300">
            <tr>
                <th class="px-6 py-4 text-left">Peminjam</th>
                <th class="px-6 py-4 text-left">Buku</th>
                <th class="px-6 py-4 text-left">Tgl Pinjam</th>
                <th class="px-6 py-4 text-left">Batas</th>
                <th class="px-6 py-4 text-left">Status</th>
                <th class="px-6 py-4 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y dark:divide-gray-700">
            @forelse($borrowings as $b)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                <td class="px-6 py-4">
                    <p class="font-medium dark:text-white">{{ $b->user->name }}</p>
                    <p class="text-gray-400 text-xs">{{ $b->user->email }}</p>
                </td>
                <td class="px-6 py-4">
                    @foreach($b->borrowingDetails as $d)
                        <p class="text-gray-600 dark:text-gray-300 text-xs">{{ $d->book->title }}</p>
                    @endforeach
                </td>
                <td class="px-6 py-4 text-gray-500 dark:text-gray-400">{{ $b->borrow_date->format('d/m/Y') }}</td>
                <td class="px-6 py-4 text-gray-500 dark:text-gray-400">{{ $b->due_date->format('d/m/Y') }}</td>
                <td class="px-6 py-4">
                    @php $colors = ['pending'=>'bg-yellow-100 text-yellow-700','approved'=>'bg-blue-100 text-blue-700','borrowed'=>'bg-indigo-100 text-indigo-700','returned'=>'bg-green-100 text-green-700','rejected'=>'bg-red-100 text-red-700','expired'=>'bg-gray-100 text-gray-700']; @endphp
                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $colors[$b->status] ?? 'bg-gray-100' }}">
                        {{ ucfirst($b->status) }}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center justify-center gap-2 flex-wrap">
                        <a href="{{ route('admin.borrowings.show', $b) }}" class="text-xs px-3 py-1.5 bg-gray-100 dark:bg-gray-600 text-gray-600 dark:text-gray-200 rounded-lg hover:bg-gray-200">
                            Detail
                        </a>
                        @if($b->status === 'pending')
                            <form action="{{ route('admin.borrowings.approve', $b) }}" method="POST">
                                @csrf @method('PATCH')
                                <button class="text-xs px-3 py-1.5 bg-green-100 text-green-600 rounded-lg hover:bg-green-200">Setujui</button>
                            </form>
                            <form action="{{ route('admin.borrowings.reject', $b) }}" method="POST">
                                @csrf @method('PATCH')
                                <button class="text-xs px-3 py-1.5 bg-red-100 text-red-600 rounded-lg hover:bg-red-200">Tolak</button>
                            </form>
                        @endif
                        @if($b->status === 'borrowed')
                            <form action="{{ route('admin.borrowings.return', $b) }}" method="POST" onsubmit="return confirm('Konfirmasi pengembalian?')">
                                @csrf @method('PATCH')
                                <button class="text-xs px-3 py-1.5 bg-indigo-100 text-indigo-600 rounded-lg hover:bg-indigo-200">Kembalikan</button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-12 text-center text-gray-400">Tidak ada peminjaman</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t dark:border-gray-700">{{ $borrowings->links() }}</div>
</div>
@endsection