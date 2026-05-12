<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id', 'isbn', 'title', 'author', 'publisher',
        'year', 'cover_image', 'description', 'stock',
        'location_rack', 'status'
    ];

    public function category() { return $this->belongsTo(Category::class); }
    public function borrowingDetails() { return $this->hasMany(BorrowingDetail::class); }
    public function carts() { return $this->hasMany(Cart::class); }
    public function wishlists() { return $this->hasMany(Wishlist::class); }
    public function reviews() { return $this->hasMany(Review::class); }
    public function stockLogs() { return $this->hasMany(StockLog::class); }

    public function averageRating()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }
}