@extends('layouts.app')
@section('title', 'Wishlist Saya')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-10">
    <h1 class="text-2xl font-bold mb-8 dark:text-white"><i class="fas fa-heart mr-2 text-red-500"></i>Wishlist Saya</h1>

    @if($wishlists->isEmpty())
        <div class="text-center py-20 text-gray-400 bg-white dark:bg-gray-800 rounded-2xl shadow">
            <i class="fas fa-heart text-5xl mb-4"></i>
            <p class="text-xl mb-4">Wishlist masih kosong</p>
            <a href="{{ route('catalog.index') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-xl hover:bg-indigo-700">Jelajahi Buku</a>
        </div>
    @else
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($wishlists as $wishlist)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-5 flex gap-4">
                <div class="w-14 h-18 bg-indigo-50 dark:bg-gray-700 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-book text-2xl text-indigo-300"></i>
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-sm dark:text-white line-clamp-2">{{ $wishlist->book->title }}</h3>
                    <p class="text-xs text-gray-400 mt-1">{{ $wishlist->book->author }}</p>
                    <div class="flex gap-2 mt-3">
                        <a href="{{ route('catalog.show', $wishlist->book) }}" class="text-xs text-indigo-600 hover:underline">Lihat Detail</a>
                        <form action="{{ route('wishlist.toggle', $wishlist->book) }}" method="POST">
                            @csrf
                            <button class="text-xs text-red-400 hover:text-red-600">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection