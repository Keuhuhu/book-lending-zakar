<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; color: #1f2937; }
        .container { padding: 30px; }

        .header { text-align: center; margin-bottom: 24px; }
        .header h1 { font-size: 20px; color: #4f46e5; font-weight: bold; }
        .header p { color: #6b7280; font-size: 11px; margin-top: 4px; }

        .meta { background: #f3f4f6; border-radius: 8px; padding: 10px 16px; margin-bottom: 20px; font-size: 11px; color: #4b5563; }

        table { width: 100%; border-collapse: collapse; }
        thead { background: #4f46e5; color: #fff; }
        thead th { padding: 10px 12px; text-align: left; font-size: 11px; font-weight: bold; }
        tbody tr:nth-child(even) { background: #f9fafb; }
        tbody td { padding: 8px 12px; font-size: 11px; border-bottom: 1px solid #e5e7eb; vertical-align: top; }

        .badge { display: inline-block; padding: 2px 10px; border-radius: 20px; font-size: 10px; font-weight: bold; }
        .badge-pending  { background: #fef3c7; color: #d97706; }
        .badge-approved { background: #dbeafe; color: #2563eb; }
        .badge-borrowed { background: #e0e7ff; color: #4338ca; }
        .badge-returned { background: #d1fae5; color: #059669; }
        .badge-rejected { background: #fee2e2; color: #dc2626; }

        .footer { text-align: center; margin-top: 24px; font-size: 10px; color: #9ca3af; border-top: 1px solid #e5e7eb; padding-top: 12px; }

        .summary { display: table; width: 100%; margin-bottom: 20px; }
        .summary-item { display: table-cell; text-align: center; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 12px; }
        .summary-item + .summary-item { margin-left: 8px; }
        .summary-num { font-size: 20px; font-weight: bold; color: #4f46e5; }
        .summary-label { font-size: 10px; color: #6b7280; margin-top: 2px; }
    </style>
</head>
<body>
<div class="container">

    <div class="header">
        <h1>📊 Laporan Peminjaman Buku</h1>
        <p>BookLending &bull; Dicetak {{ now()->format('d M Y, H:i') }}</p>
    </div>

    <div class="meta">
        @if(request('from') || request('to'))
            Periode: {{ request('from') ? \Carbon\Carbon::parse(request('from'))->format('d M Y') : 'Awal' }}
            s/d {{ request('to') ? \Carbon\Carbon::parse(request('to'))->format('d M Y') : 'Sekarang' }}
        @else
            Periode: Semua Waktu
        @endif
        @if(request('status'))
            &bull; Status: {{ ucfirst(request('status')) }}
        @endif
        &bull; Total: {{ $borrowings->count() }} data
    </div>

    {{-- Summary --}}
    @php
        $totalReturned = $borrowings->where('status', 'returned')->count();
        $totalBorrowed = $borrowings->whereIn('status', ['borrowed','approved'])->count();
        $totalFines    = $borrowings->sum('total_fine');
    @endphp
    <table style="margin-bottom:20px;">
        <tr>
            <td style="width:33%; padding:10px; text-align:center; background:#eef2ff; border-radius:8px;">
                <div style="font-size:20px; font-weight:bold; color:#4f46e5;">{{ $borrowings->count() }}</div>
                <div style="font-size:10px; color:#6b7280;">Total Transaksi</div>
            </td>
            <td style="width:4%;"></td>
            <td style="width:33%; padding:10px; text-align:center; background:#d1fae5; border-radius:8px;">
                <div style="font-size:20px; font-weight:bold; color:#059669;">{{ $totalReturned }}</div>
                <div style="font-size:10px; color:#6b7280;">Dikembalikan</div>
            </td>
            <td style="width:4%;"></td>
            <td style="width:33%; padding:10px; text-align:center; background:#fee2e2; border-radius:8px;">
                <div style="font-size:20px; font-weight:bold; color:#dc2626;">Rp {{ number_format($totalFines, 0, ',', '.') }}</div>
                <div style="font-size:10px; color:#6b7280;">Total Denda</div>
            </td>
        </tr>
    </table>

    {{-- Table --}}
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Peminjam</th>
                <th>Buku</th>
                <th>Tgl Pinjam</th>
                <th>Batas</th>
                <th>Status</th>
                <th>Denda</th>
            </tr>
        </thead>
        <tbody>
            @forelse($borrowings as $i => $b)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>
                    {{ $b->user->name }}<br>
                    <span style="color:#9ca3af; font-size:10px;">{{ $b->user->email }}</span>
                </td>
                <td>
                    @foreach($b->borrowingDetails as $d)
                        <span style="display:block;">{{ $d->book->title }}</span>
                    @endforeach
                </td>
                <td>{{ \Carbon\Carbon::parse($b->borrow_date)->format('d/m/Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($b->due_date)->format('d/m/Y') }}</td>
                <td><span class="badge badge-{{ $b->status }}">{{ ucfirst($b->status) }}</span></td>
                <td>{{ $b->total_fine > 0 ? 'Rp '.number_format($b->total_fine,0,',','.') : '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center; padding:20px; color:#9ca3af;">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        BookLending &copy; {{ date('Y') }} &bull; Laporan ini digenerate otomatis oleh sistem
    </div>
</div>
</body>
</html>