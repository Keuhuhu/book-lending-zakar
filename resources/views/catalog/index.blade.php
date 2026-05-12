@extends('layouts.app')
@section('title', 'Katalog Buku')

@section('content')
{{-- Hero Section dengan Background Image Langsung dari URL --}}
<div class="relative bg-cover bg-center text-white py-24" style="background-image: url('{{ asset('images/pexels-books-1281581.jpg') }}');">
    {{-- Overlay Gelap agar teks tetap terbaca dengan jelas --}}
    <div class="absolute inset-0 bg-gray-900/70 dark:bg-gray-900/80"></div>
    
    <div class="relative max-w-7xl mx-auto px-4 text-center z-10">
        <h1 class="text-4xl md:text-5xl font-bold mb-4 drop-shadow-lg">📚 Katalog Perpustakaan</h1>
        <p class="text-gray-200 mb-8 text-lg md:text-xl drop-shadow-md">Temukan buku favoritmu dan mulai meminjam sekarang</p>
        <form action="{{ route('catalog.index') }}" method="GET" class="flex max-w-lg mx-auto shadow-2xl">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari judul, penulis..."
                class="flex-1 px-5 py-4 rounded-l-full text-gray-800 outline-none focus:ring-2 focus:ring-indigo-500">
            <button class="bg-yellow-400 text-gray-800 px-8 py-4 rounded-r-full font-bold hover:bg-yellow-300 transition-colors">
                Cari
            </button>
        </form>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-10">
    {{-- Filter Kategori --}}
    <div class="flex gap-3 flex-wrap mb-8">
        <a href="{{ route('catalog.index') }}"
            class="px-4 py-2 rounded-full text-sm font-medium {{ !request('category') ? 'bg-indigo-600 text-white shadow-md' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 border dark:border-gray-700 hover:bg-indigo-50 dark:hover:bg-gray-700 transition-colors' }}">
            Semua
        </a>
        @foreach($categories as $cat)
            <a href="{{ route('catalog.index', ['category' => $cat->id]) }}"
                class="px-4 py-2 rounded-full text-sm font-medium {{ request('category') == $cat->id ? 'bg-indigo-600 text-white shadow-md' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 border dark:border-gray-700 hover:bg-indigo-50 dark:hover:bg-gray-700 transition-colors' }}">
                {{ $cat->name }}
            </a>
        @endforeach
    </div>

    {{-- Grid Buku --}}
    @if($books->isEmpty())
        <div class="text-center py-20 text-gray-400">
            <i class="fas fa-search text-5xl mb-4"></i>
            <p class="text-xl">Buku tidak ditemukan</p>
        </div>
    @else
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6">
            @foreach($books as $book)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow hover:shadow-xl transition-all duration-300 hover:-translate-y-1 overflow-hidden group border border-gray-100 dark:border-gray-700">
                <a href="{{ route('catalog.show', $book) }}">
                    <div class="h-56 bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-gray-700 dark:to-gray-600 flex items-center justify-center overflow-hidden">
                        @if($book->cover_image)
                            <img src="{{ asset('storage/'.$book->cover_image) }}" alt="{{ $book->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <i class="fas fa-book text-4xl text-indigo-300"></i>
                        @endif
                    </div>
                </a>
                <div class="p-4">
                    <span class="text-xs text-indigo-600 dark:text-indigo-400 font-bold bg-indigo-50 dark:bg-indigo-900/30 px-2 py-1 rounded-md">{{ $book->category->name }}</span>
                    <a href="{{ route('catalog.show', $book) }}" class="block mt-2">
                        <h3 class="font-semibold text-sm line-clamp-2 hover:text-indigo-600 dark:hover:text-indigo-400 dark:text-white transition-colors">{{ $book->title }}</h3>
                    </a>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $book->author }}</p>
                    <div class="flex items-center justify-between mt-3">
                        <span class="text-xs font-medium {{ $book->stock > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-500 dark:text-red-400' }}">
                            <i class="fas fa-circle text-[10px] mr-1"></i>
                            {{ $book->stock > 0 ? 'Tersedia ('.$book->stock.')' : 'Habis' }}
                        </span>
                    </div>
                    @auth
                        @if(auth()->user()->role === 'user')
                            <form action="{{ route('cart.add', $book) }}" method="POST" class="mt-4">
                                @csrf
                                <button class="w-full bg-indigo-600 text-white text-xs py-2.5 rounded-xl font-medium hover:bg-indigo-700 disabled:opacity-50 transition-colors shadow-sm"
                                    {{ $book->stock <= 0 ? 'disabled' : '' }}>
                                    <i class="fas fa-cart-plus mr-1"></i> Tambah
                                </button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="block mt-4 w-full bg-indigo-600 text-white text-xs py-2.5 rounded-xl font-medium text-center hover:bg-indigo-700 transition-colors shadow-sm">
                            Login untuk Pinjam
                        </a>
                    @endauth
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $books->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection