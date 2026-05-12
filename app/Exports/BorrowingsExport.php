<?php

namespace App\Exports;

use App\Models\Borrowing;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BorrowingsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Borrowing::with('user', 'borrowingDetails.book');

        if (!empty($this->filters['from'])) {
            $query->whereDate('created_at', '>=', $this->filters['from']);
        }
        if (!empty($this->filters['to'])) {
            $query->whereDate('created_at', '<=', $this->filters['to']);
        }
        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        return $query->latest()->get();
    }

    public function headings(): array
    {
        return [
            'No. Peminjaman',
            'Nama Peminjam',
            'Email',
            'Buku Dipinjam',
            'Tgl Pinjam',
            'Batas Kembali',
            'Tgl Kembali',
            'Status',
            'Total Denda',
        ];
    }

    public function map($borrowing): array
    {
        $books = $borrowing->borrowingDetails
            ->map(fn($d) => $d->book->title)
            ->implode(', ');

        return [
            '#' . str_pad($borrowing->id, 6, '0', STR_PAD_LEFT),
            $borrowing->user->name,
            $borrowing->user->email,
            $books,
            $borrowing->borrow_date?->format('d/m/Y'),
            $borrowing->due_date?->format('d/m/Y'),
            $borrowing->return_date?->format('d/m/Y') ?? '-',
            ucfirst($borrowing->status),
            $borrowing->total_fine > 0 ? 'Rp ' . number_format($borrowing->total_fine, 0, ',', '.') : '-',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill'      => ['fillType' => 'solid', 'startColor' => ['argb' => 'FF4F46E5']],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
    }
}