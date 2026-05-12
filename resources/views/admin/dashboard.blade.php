@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')

{{-- Metric Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-book text-indigo-500 text-xl"></i>
        </div>
        <div>
            <p class="text-sm text-gray-400">Total Buku</p>
            <p class="text-2xl font-bold dark:text-white">{{ $totalBooks }}</p>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-green-100 dark:bg-green-900 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-users text-green-500 text-xl"></i>
        </div>
        <div>
            <p class="text-sm text-gray-400">Total Pengguna</p>
            <p class="text-2xl font-bold dark:text-white">{{ $totalUsers }}</p>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-yellow-100 dark:bg-yellow-900 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-hand-holding-heart text-yellow-500 text-xl"></i>
        </div>
        <div>
            <p class="text-sm text-gray-400">Sedang Dipinjam</p>
            <p class="text-2xl font-bold dark:text-white">{{ $activeBorrows }}</p>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-red-100 dark:bg-red-900 flex items-center justify-center flex-shrink-0">
            <i class="fas fa-money-bill text-red-500 text-xl"></i>
        </div>
        <div>
            <p class="text-sm text-gray-400">Total Denda Belum Bayar</p>
            <p class="text-2xl font-bold text-red-500">Rp {{ number_format($unpaidFines, 0, ',', '.') }}</p>
        </div>
    </div>
</div>

{{-- Chart + Recent Table --}}
<div class="grid xl:grid-cols-3 gap-6">

    {{-- Chart --}}
    <div class="xl:col-span-1 bg-white dark:bg-gray-800 rounded-2xl shadow p-6">
        <h3 class="font-semibold text-gray-700 dark:text-gray-200 mb-4">Peminjaman per Bulan ({{ date('Y') }})</h3>
        <canvas id="borrowingChart" height="260"></canvas>
    </div>

    {{-- Recent Borrowings --}}
    <div class="xl:col-span-2 bg-white dark:bg-gray-800 rounded-2xl shadow overflow-hidden">
        <div class="px-6 py-4 border-b dark:border-gray-700 flex items-center justify-between">
            <h3 class="font-semibold text-gray-700 dark:text-gray-200">Peminjaman Terbaru</h3>
            <a href="{{ route('admin.borrowings.index') }}" class="text-xs text-indigo-500 hover:underline">Lihat semua →</a>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700 text-gray-400 dark:text-gray-300">
                <tr>
                    <th class="px-6 py-3 text-left">Peminjam</th>
                    <th class="px-6 py-3 text-left">Buku</th>
                    <th class="px-6 py-3 text-left">Status</th>
                    <th class="px-6 py-3 text-left">Tanggal</th>
                </tr>
            </thead>
            <tbody class="divide-y dark:divide-gray-700">
                @forelse($recentBorrowings as $b)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <td class="px-6 py-3 font-medium dark:text-white">{{ $b->user->name }}</td>
                    <td class="px-6 py-3 text-gray-500 dark:text-gray-400 text-xs">
                        @foreach($b->borrowingDetails as $d)
                            <span class="block">{{ $d->book->title }}</span>
                        @endforeach
                    </td>
                    <td class="px-6 py-3">
                        @php
                            $colors = [
                                'pending'  => 'bg-yellow-100 text-yellow-700',
                                'approved' => 'bg-blue-100 text-blue-700',
                                'borrowed' => 'bg-indigo-100 text-indigo-700',
                                'returned' => 'bg-green-100 text-green-700',
                                'rejected' => 'bg-red-100 text-red-700',
                                'expired'  => 'bg-gray-100 text-gray-700',
                            ];
                        @endphp
                        <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $colors[$b->status] ?? 'bg-gray-100' }}">
                            {{ ucfirst($b->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-3 text-gray-400 text-xs">{{ $b->created_at->format('d M Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-gray-400">Belum ada peminjaman</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const monthlyData = @json($monthlyData);

    const labels = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    const data = Array(12).fill(0);
    monthlyData.forEach(d => { data[d.month - 1] = d.total; });

    new Chart(document.getElementById('borrowingChart'), {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label: 'Peminjaman',
                data,
                backgroundColor: 'rgba(99,102,241,0.7)',
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
    });
});
</script>

@endsection