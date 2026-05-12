@extends('layouts.app')
@section('title', 'Keranjang Peminjaman')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-10">
    <h1 class="text-2xl font-bold mb-8 dark:text-white"><i class="fas fa-shopping-cart mr-2 text-indigo-600"></i>Keranjang Peminjaman</h1>

    @if($carts->isEmpty())
        <div class="text-center py-20 text-gray-400 bg-white dark:bg-gray-800 rounded-2xl shadow">
            <i class="fas fa-shopping-cart text-5xl mb-4"></i>
            <p class="text-xl mb-4">Keranjang masih kosong</p>
            <a href="{{ route('catalog.index') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-xl hover:bg-indigo-700">Cari Buku</a>
        </div>
    @else
        <div class="grid md:grid-cols-3 gap-6">
            <div class="md:col-span-2 space-y-4">
                @foreach($carts as $cart)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-5 flex items-center gap-4">
                    <div class="w-16 h-20 bg-indigo-50 dark:bg-gray-700 rounded-xl flex items-center justify-center flex-shrink-0">
                        @if($cart->book->cover_image)
                            <img src="{{ asset('storage/'.$cart->book->cover_image) }}" class="w-full h-full object-cover rounded-xl">
                        @else
                            <i class="fas fa-book text-2xl text-indigo-300"></i>
                        @endif
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold dark:text-white">{{ $cart->book->title }}</h3>
                        <p class="text-sm text-gray-400">{{ $cart->book->author }}</p>
                        <span class="text-xs text-green-500 mt-1 block">Stok: {{ $cart->book->stock }}</span>
                    </div>
                    <form action="{{ route('cart.remove', $cart) }}" method="POST">
                        @csrf @method('DELETE')
                        <button class="text-red-400 hover:text-red-600">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
                @endforeach
            </div>

            {{-- Summary --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6 h-fit">
                <h2 class="font-bold text-lg mb-4 dark:text-white">Ringkasan</h2>
                <div class="space-y-3 text-sm text-gray-600 dark:text-gray-300">
                    <div class="flex justify-between">
                        <span>Jumlah Buku</span>
                        <span class="font-semibold">{{ $carts->count() }} buku</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Durasi Pinjam</span>
                        <span class="font-semibold">7 hari</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Denda/hari</span>
                        <span class="font-semibold">Rp 2.000</span>
                    </div>
                </div>
                <hr class="my-4 dark:border-gray-700">
                <form action="{{ route('checkout') }}" method="POST">
                    @csrf
                    <button class="w-full bg-indigo-600 text-white py-3 rounded-xl hover:bg-indigo-700 font-semibold">
                        <i class="fas fa-check mr-2"></i>Ajukan Peminjaman
                    </button>
                </form>
                <a href="{{ route('catalog.index') }}" class="block text-center text-sm text-indigo-600 mt-3 hover:underline">Tambah Buku Lagi</a>
            </div>
        </div>
    @endif
</div>
@endsection