@extends('layouts.admin')
@section('title', 'Edit Buku')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-8">
        <h2 class="text-xl font-bold mb-6 dark:text-white">Edit Buku</h2>
        <form action="{{ route('admin.books.update', $book) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf @method('PUT')
            <div class="grid grid-cols-2 gap-5">
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Judul Buku</label>
                    <input type="text" name="title" value="{{ old('title', $book->title) }}" required
                        class="w-full border dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Penulis</label>
                    <input type="text" name="author" value="{{ old('author', $book->author) }}" required
                        class="w-full border dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Penerbit</label>
                    <input type="text" name="publisher" value="{{ old('publisher', $book->publisher) }}" required
                        class="w-full border dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kategori</label>
                    <select name="category_id" required class="w-full border dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $book->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tahun Terbit</label>
                    <input type="number" name="year" value="{{ old('year', $book->year) }}" required
                        class="w-full border dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Stok</label>
                    <input type="number" name="stock" value="{{ old('stock', $book->stock) }}" min="0" required
                        class="w-full border dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Lokasi Rak</label>
                    <input type="text" name="location_rack" value="{{ old('location_rack', $book->location_rack) }}"
                        class="w-full border dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Deskripsi</label>
                    <textarea name="description" rows="4"
                        class="w-full border dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">{{ old('description', $book->description) }}</textarea>
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cover Buku</label>
                    @if($book->cover_image)
                        <img src="{{ asset('storage/'.$book->cover_image) }}" class="h-24 rounded-xl mb-2">
                    @endif
                    <input type="file" name="cover_image" accept="image/*"
                        class="w-full border dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl px-4 py-2.5">
                </div>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2.5 rounded-xl hover:bg-indigo-700 font-semibold">
                    <i class="fas fa-save mr-2"></i>Update
                </button>
                <a href="{{ route('admin.books.index') }}" class="border dark:border-gray-600 text-gray-600 dark:text-gray-300 px-6 py-2.5 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection