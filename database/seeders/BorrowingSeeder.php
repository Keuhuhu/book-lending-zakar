<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Borrowing;
use App\Models\BorrowingDetail;
use Carbon\Carbon;

class BorrowingSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['user_id' => 2, 'book_id' => 1, 'status' => 'returned', 'days_ago' => 20],
            ['user_id' => 2, 'book_id' => 3, 'status' => 'returned', 'days_ago' => 15],
            ['user_id' => 3, 'book_id' => 5, 'status' => 'borrowed', 'days_ago' => 5],
            ['user_id' => 4, 'book_id' => 8, 'status' => 'borrowed', 'days_ago' => 3],
            ['user_id' => 5, 'book_id' => 13, 'status' => 'pending', 'days_ago' => 1],
            ['user_id' => 6, 'book_id' => 16, 'status' => 'approved', 'days_ago' => 2],
        ];

        foreach ($data as $item) {
            $borrowDate = Carbon::now()->subDays($item['days_ago']);

            $borrowing = Borrowing::create([
                'user_id'     => $item['user_id'],
                'borrow_date' => $borrowDate,
                'due_date'    => $borrowDate->copy()->addDays(7),
                'return_date' => $item['status'] === 'returned' ? $borrowDate->copy()->addDays(6) : null,
                'status'      => $item['status'],
            ]);

            BorrowingDetail::create([
                'borrowing_id' => $borrowing->id,
                'book_id'      => $item['book_id'],
            ]);
        }
    }
}