<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Borrowing extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'borrow_date', 'due_date', 'return_date',
        'status', 'total_fine', 'fine_paid', 'qr_code', 'notes'
    ];

    protected $casts = [
        'borrow_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
        'fine_paid' => 'boolean',
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function borrowingDetails() { return $this->hasMany(BorrowingDetail::class); }
    public function books() { return $this->belongsToMany(Book::class, 'borrowing_details'); }
    public function fine() { return $this->hasOne(Fine::class); }
}