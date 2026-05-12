@extends('layouts.app')
@section('title', $book->title)

@section('content')
<div class="max-w-6xl mx-auto px-4 py-10">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden">
        <div class="md:flex">
            {{-- Cover --}}
            <div class="md:w-64 bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-gray-700 dark:to-gray-600 flex items-center justify-center p-8 min-h-64">
                @if($book->cover_image)
                    <img src="{{ asset('storage/'.$book->cover_image) }}" class="max-h-64 rounded-xl shadow-md">
                @else
                    <i class="fas fa-book text-6xl text-indigo-300"></i>
                @endif
            </div>

            {{-- Info --}}
            <div class="flex-1 p-8">
                <span class="bg-indigo-100 text-indigo-600 text-xs font-semibold px-3 py-1 rounded-full">{{ $book->category->name }}</span>
                <h1 class="text-3xl font-bold mt-3 dark:text-white">{{ $book->title }}</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1">oleh <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $book->author }}</span></p>

                {{-- Rating --}}
                <div class="flex items-center gap-2 mt-3">
                    @php $avg = round($book->averageRating(), 1) @endphp
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star {{ $i <= $avg ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                    @endfor
                    <span class="text-sm text-gray-500">({{ $book->reviews->count() }} ulasan)</span>
                </div>

                <div class="grid grid-cols-2 gap-4 mt-6 text-sm">
                    <div><span class="text-gray-400">Penerbit:</span> <span class="font-medium dark:text-gray-200">{{ $book->publisher }}</span></div>
                    <div><span class="text-gray-400">Tahun:</span> <span class="font-medium dark:text-gray-200">{{ $book->year }}</span></div>
                    <div><span class="text-gray-400">Rak:</span> <span class="font-medium dark:text-gray-200">{{ $book->location_rack ?? '-' }}</span></div>
                    <div><span class="text-gray-400">Stok:</span>
                        <span class="font-semibold {{ $book->stock > 0 ? 'text-green-500' : 'text-red-500' }}">
                            {{ $book->stock > 0 ? $book->stock.' tersedia' : 'Tidak tersedia' }}
                        </span>
                    </div>
                </div>

                <p class="mt-6 text-gray-600 dark:text-gray-300 leading-relaxed">{{ $book->description }}</p>

                {{-- Actions --}}
                <div class="flex gap-3 mt-8">
                    @auth
                        @if(auth()->user()->role === 'user')
                            <form action="{{ route('cart.add', $book) }}" method="POST">
                                @csrf
                                <button class="bg-indigo-600 text-white px-6 py-3 rounded-xl hover:bg-indigo-700 font-semibold {{ $book->stock <= 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                    {{ $book->stock <= 0 ? 'disabled' : '' }}>
                                    <i class="fas fa-cart-plus mr-2"></i>Tambah ke Keranjang
                                </button>
                            </form>
                            <form action="{{ route('wishlist.toggle', $book) }}" method="POST">
                                @csrf
                                <button class="border border-red-400 text-red-400 px-6 py-3 rounded-xl hover:bg-red-50 font-semibold">
                                    <i class="fas fa-heart mr-2"></i>Wishlist
                                </button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-xl hover:bg-indigo-700 font-semibold">
                            Login untuk Meminjam
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    {{-- Reviews --}}
    <div class="mt-10 bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-8">
        <h2 class="text-xl font-bold mb-6 dark:text-white">Ulasan Pembaca</h2>
        @forelse($book->reviews as $review)
            <div class="border-b dark:border-gray-700 pb-4 mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold text-sm">
                        {{ strtoupper(substr($review->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-semibold text-sm dark:text-gray-200">{{ $review->user->name }}</p>
                        <div class="flex">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star text-xs {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                            @endfor
                        </div>
                    </div>
                </div>
                @if($review->comment)
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300 ml-12">{{ $review->comment }}</p>
                @endif
            </div>
        @empty
            <p class="text-gray-400 text-sm">Belum ada ulasan untuk buku ini.</p>
        @endforelse
    </div>

    {{-- Buku Serupa --}}
    @if($related->count())
    <div class="mt-10">
        <h2 class="text-xl font-bold mb-6 dark:text-white">Buku Serupa</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($related as $rel)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow hover:shadow-lg transition p-4">
                <a href="{{ route('catalog.show', $rel) }}">
                    <div class="h-32 bg-indigo-50 dark:bg-gray-700 rounded-xl flex items-center justify-center mb-3">
                        <i class="fas fa-book text-3xl text-indigo-300"></i>
                    </div>
                    <h3 class="font-semibold text-sm line-clamp-2 hover:text-indigo-600">{{ $rel->title }}</h3>
                    <p class="text-xs text-gray-400 mt-1">{{ $rel->author }}</p>
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection