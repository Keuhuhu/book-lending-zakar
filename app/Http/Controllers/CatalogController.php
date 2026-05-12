<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with('category')->where('status', 'available');

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%'.$request->search.'%')
                  ->orWhere('author', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->category) {
            $query->where('category_id', $request->category);
        }

        $books = $query->latest()->paginate(12);
        $categories = Category::all();

        return view('catalog.index', compact('books', 'categories'));
    }

    public function show(Book $book)
    {
        $book->load('category', 'reviews.user');
        $related = Book::where('category_id', $book->category_id)
            ->where('id', '!=', $book->id)
            ->limit(4)->get();

        return view('catalog.show', compact('book', 'related'));
    }
}