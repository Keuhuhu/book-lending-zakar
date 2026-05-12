@extends('layouts.admin')
@section('title', 'Manajemen Buku')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-xl font-bold dark:text-white">Daftar Buku</h2>
    <a href="{{ route('admin.books.create') }}" class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl hover:bg-indigo-700 text-sm font-semibold">
        <i class="fas fa-plus mr-2"></i>Tambah Buku
    </a>
</div>

<div class="bg-white dark:bg-gray-800 rounded-2xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-300">
            <tr>
                <th class="px-6 py-4 text-left">Buku</th>
                <th class="px-6 py-4 text-left">Kategori</th>
                <th class="px-6 py-4 text-left">Stok</th>
                <th class="px-6 py-4 text-left">Rak</th>
                <th class="px-6 py-4 text-left">Status</th>
                <th class="px-6 py-4 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y dark:divide-gray-700">
            @forelse($books as $book)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-12 bg-indigo-50 dark:bg-gray-600 rounded-lg flex items-center justify-center flex-shrink-0">
                            @if($book->cover_image)
                                <img src="{{ asset('storage/'.$book->cover_image) }}" class="w-full h-full object-cover rounded-lg">
                            @else
                                <i class="fas fa-book text-indigo-300"></i>
                            @endif
                        </div>
                        <div>
                            <p class="font-semibold dark:text-white">{{ $book->title }}</p>
                            <p class="text-gray-400 text-xs">{{ $book->author }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 text-gray-600 dark:text-gray-300">{{ $book->category->name }}</td>
                <td class="px-6 py-4">
                    <span class="font-semibold {{ $book->stock > 0 ? 'text-green-500' : 'text-red-400' }}">{{ $book->stock }}</span>
                </td>
                <td class="px-6 py-4 text-gray-500 dark:text-gray-400">{{ $book->location_rack ?? '-' }}</td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $book->status === 'available' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                        {{ $book->status === 'available' ? 'Tersedia' : 'Tidak Tersedia' }}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center justify-center gap-2">
                        <a href="{{ route('admin.books.edit', $book) }}" class="text-indigo-600 hover:text-indigo-800 px-3 py-1.5 bg-indigo-50 dark:bg-indigo-900 rounded-lg text-xs font-medium">
                            <i class="fas fa-edit mr-1"></i>Edit
                        </a>
                        <form action="{{ route('admin.books.destroy', $book) }}" method="POST" onsubmit="return confirm('Hapus buku ini?')">
                            @csrf @method('DELETE')
                            <button class="text-red-500 hover:text-red-700 px-3 py-1.5 bg-red-50 dark:bg-red-900 rounded-lg text-xs font-medium">
                                <i class="fas fa-trash mr-1"></i>Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                    <i class="fas fa-book text-4xl mb-3"></i>
                    <p>Belum ada buku</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t dark:border-gray-700">
        {{ $books->links() }}
    </div>
</div>
@endsection