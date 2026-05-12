<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 13px; color: #1f2937; background: #fff; }
        .container { padding: 40px; max-width: 600px; margin: 0 auto; }

        .header { text-align: center; border-bottom: 2px solid #4f46e5; padding-bottom: 20px; margin-bottom: 24px; }
        .header h1 { font-size: 22px; color: #4f46e5; font-weight: bold; }
        .header p { color: #6b7280; font-size: 12px; margin-top: 4px; }

        .badge { display: inline-block; padding: 4px 14px; border-radius: 20px; font-size: 11px; font-weight: bold; }
        .badge-pending  { background: #fef3c7; color: #d97706; }
        .badge-approved { background: #dbeafe; color: #2563eb; }
        .badge-borrowed { background: #e0e7ff; color: #4338ca; }
        .badge-returned { background: #d1fae5; color: #059669; }
        .badge-rejected { background: #fee2e2; color: #dc2626; }

        .section { margin-bottom: 20px; }
        .section-title { font-size: 11px; font-weight: bold; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px; border-bottom: 1px solid #e5e7eb; padding-bottom: 6px; }

        .info-grid { display: table; width: 100%; }
        .info-row { display: table-row; }
        .info-label { display: table-cell; color: #6b7280; font-size: 12px; padding: 4px 0; width: 140px; }
        .info-value { display: table-cell; font-weight: 600; font-size: 12px; padding: 4px 0; }

        .book-item { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 10px 14px; margin-bottom: 8px; }
        .book-title { font-weight: bold; font-size: 13px; }
        .book-author { color: #6b7280; font-size: 11px; margin-top: 2px; }

        .qr-section { text-align: center; margin-top: 24px; padding: 20px; border: 1px dashed #c7d2fe; border-radius: 12px; background: #eef2ff; }
        .qr-section p { color: #4f46e5; font-size: 11px; margin-top: 8px; font-weight: bold; }

        .fine-box { background: #fee2e2; border: 1px solid #fca5a5; border-radius: 8px; padding: 12px 16px; margin-top: 16px; }
        .fine-box p { color: #dc2626; }

        .footer { text-align: center; margin-top: 30px; font-size: 10px; color: #9ca3af; border-top: 1px solid #e5e7eb; padding-top: 16px; }
    </style>
</head>
<body>
<div class="container">

    {{-- Header --}}
    <div class="header">
        <h1>📚 BookLending</h1>
        <p>E-Receipt Peminjaman Buku</p>
        <p style="margin-top:8px;">
            <span class="badge badge-{{ $borrowing->status }}">{{ ucfirst($borrowing->status) }}</span>
        </p>
    </div>

    {{-- Info Peminjaman --}}
    <div class="section">
        <div class="section-title">Informasi Peminjaman</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">No. Peminjaman</div>
                <div class="info-value">#{{ str_pad($borrowing->id, 6, '0', STR_PAD_LEFT) }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Tanggal Pinjam</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($borrowing->borrow_date)->format('d M Y') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Batas Kembali</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($borrowing->due_date)->format('d M Y') }}</div>
            </div>
            @if($borrowing->return_date)
            <div class="info-row">
                <div class="info-label">Tanggal Kembali</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($borrowing->return_date)->format('d M Y') }}</div>
            </div>
            @endif
        </div>
    </div>

    {{-- Info Peminjam --}}
    <div class="section">
        <div class="section-title">Data Peminjam</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Nama</div>
                <div class="info-value">{{ $borrowing->user->name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Email</div>
                <div class="info-value">{{ $borrowing->user->email }}</div>
            </div>
        </div>
    </div>

    {{-- Buku --}}
    <div class="section">
        <div class="section-title">Buku yang Dipinjam</div>
        @foreach($borrowing->borrowingDetails as $detail)
        <div class="book-item">
            <div class="book-title">{{ $detail->book->title }}</div>
            <div class="book-author">{{ $detail->book->author }} &bull; {{ $detail->book->publisher }}</div>
        </div>
        @endforeach
    </div>

    {{-- Denda --}}
    @if($borrowing->fine)
    <div class="fine-box">
        <p><strong>⚠ Denda Keterlambatan</strong></p>
        <p style="margin-top:4px;">{{ $borrowing->fine->days_late }} hari × Rp 2.000 = <strong>Rp {{ number_format($borrowing->fine->amount, 0, ',', '.') }}</strong></p>
        <p style="font-size:11px; margin-top:4px;">Status: {{ $borrowing->fine->status === 'paid' ? 'Lunas ✓' : 'Belum Dibayar' }}</p>
    </div>
    @endif

    {{-- QR Code --}}
    @if($borrowing->qr_code && file_exists(public_path($borrowing->qr_code)))
    <div class="qr-section">
        <img src="{{ public_path($borrowing->qr_code) }}" width="140" height="140">
        <p>Scan QR Code untuk verifikasi peminjaman</p>
    </div>
    @endif

    <div class="footer">
        Dicetak pada {{ now()->format('d M Y, H:i') }} &bull; BookLending &copy; {{ date('Y') }}
    </div>
</div>
</body>
</html>