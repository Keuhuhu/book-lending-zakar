<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockLog extends Model
{
    protected $fillable = ['book_id', 'type', 'quantity', 'reason', 'admin_id'];

    public function book() { return $this->belongsTo(Book::class); }
    public function admin() { return $this->belongsTo(User::class, 'admin_id'); }
}