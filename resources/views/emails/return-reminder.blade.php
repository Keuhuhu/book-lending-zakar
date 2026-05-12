<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f3f4f6; margin: 0; padding: 0; }
        .container { max-width: 560px; margin: 40px auto; background: #fff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #4f46e5, #7c3aed); padding: 32px 40px; text-align: center; }
        .header h1 { color: #fff; margin: 0; font-size: 22px; }
        .header p { color: #c7d2fe; margin: 8px 0 0; font-size: 14px; }
        .body { padding: 32px 40px; }
        .body p { color: #374151; line-height: 1.6; }
        .book-list { background: #f9fafb; border-radius: 12px; padding: 16px 20px; margin: 16px 0; }
        .book-list p { margin: 4px 0; font-size: 14px; color: #4b5563; }
        .book-list strong { color: #1f2937; }
        .due-date { background: #fef3c7; border: 1px solid #fde68a; border-radius: 10px; padding: 12px 20px; text-align: center; margin: 20px 0; }
        .due-date span { font-size: 18px; font-weight: bold; color: #d97706; }
        .footer { background: #f9fafb; padding: 20px 40px; text-align: center; font-size: 12px; color: #9ca3af; }
        .btn { display: inline-block; background: #4f46e5; color: #fff; padding: 12px 28px; border-radius: 10px; text-decoration: none; font-weight: bold; margin-top: 16px; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>📚 BookLending</h1>
        <p>Sistem Peminjaman Buku Digital</p>
    </div>
    <div class="body">
        <p>Halo, <strong>{{ $user->name }}</strong>!</p>
        <p>Ini adalah pengingat bahwa buku yang kamu pinjam <strong>harus dikembalikan besok</strong>. Harap segera kembalikan buku agar tidak dikenakan denda keterlambatan.</p>

        <div class="book-list">
            <p style="font-weight:bold; color:#4f46e5; margin-bottom:8px;">📖 Buku yang dipinjam:</p>
            @foreach($borrowing->borrowingDetails as $detail)
                <p>• <strong>{{ $detail->book->title }}</strong> — {{ $detail->book->author }}</p>
            @endforeach
        </div>

        <div class="due-date">
            ⏰ Batas Pengembalian: <span>{{ \Carbon\Carbon::parse($borrowing->due_date)->format('d M Y') }}</span>
        </div>

        <p>Kembalikan buku tepat waktu untuk menghindari denda <strong>Rp 2.000/hari</strong>. Terima kasih sudah menggunakan layanan BookLending!</p>
    </div>
    <div class="footer">
        Email ini dikirim otomatis oleh sistem BookLending. Jangan balas email ini.
    </div>
</div>
</body>
</html>