<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fine extends Model
{
    protected $fillable = [
        'borrowing_id', 'user_id', 'amount', 'days_late', 'status', 'paid_at'
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function borrowing() { return $this->belongsTo(Borrowing::class); }
    public function user() { return $this->belongsTo(User::class); }
}