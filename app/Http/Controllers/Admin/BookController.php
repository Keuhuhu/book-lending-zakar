<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\StockLog;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::with('category')->latest()->paginate(15);
        return view('admin.books.index', compact('books'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id'   => 'required|exists:categories,id',
            'isbn'          => 'nullable|unique:books,isbn',
            'title'         => 'required|string|max:200',
            'author'        => 'required|string|max:100',
            'publisher'     => 'required|string|max:100',
            'year'          => 'required|integer',
            'description'   => 'nullable|string',
            'stock'         => 'required|integer|min:0',
            'location_rack' => 'nullable|string|max:20',
            'cover_image'   => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')
                ->store('covers', 'public');
        }

        $book = Book::create($data);

        StockLog::create([
            'book_id'  => $book->id,
            'type'     => 'in',
            'quantity' => $book->stock,
            'reason'   => 'Buku baru ditambahkan',
            'admin_id' => auth()->id(),
        ]);

        return redirect()->route('admin.books.index')
            ->with('success', 'Buku berhasil ditambahkan.');
    }

    public function edit(Book $book)
    {
        $categories = Category::all();
        return view('admin.books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        $data = $request->validate([
            'category_id'   => 'required|exists:categories,id',
            'title'         => 'required|string|max:200',
            'author'        => 'required|string|max:100',
            'publisher'     => 'required|string|max:100',
            'year'          => 'required|integer',
            'description'   => 'nullable|string',
            'stock'         => 'required|integer|min:0',
            'location_rack' => 'nullable|string|max:20',
            'cover_image'   => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')
                ->store('covers', 'public');
        }

        $book->update($data);
        return redirect()->route('admin.books.index')
            ->with('success', 'Buku berhasil diupdate.');
    }

    public function destroy(Book $book)
    {
        $book->delete();
        return back()->with('success', 'Buku berhasil dihapus.');
    }
}