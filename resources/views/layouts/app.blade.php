<!DOCTYPE html>
<html lang="id" x-data="{ darkMode: false, mobileMenu: false }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - @yield('title', 'Perpustakaan Digital')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { darkMode: 'class' }</script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100 min-h-screen flex flex-col">

{{-- NAVBAR --}}
<nav class="bg-white dark:bg-gray-800 shadow-md sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
        <a href="{{ route('catalog.index') }}" class="flex items-center gap-2 text-xl font-bold text-indigo-600 dark:text-indigo-400">
            <i class="fas fa-book-open"></i>
            <span>BookLending</span>
        </a>

        {{-- Nav Links (Search bar dihapus dari sini) --}}
        <div class="hidden md:flex items-center gap-4 ml-auto">
            @auth
                @if(auth()->user()->role === 'user')
                    <a href="{{ route('cart.index') }}" class="relative text-gray-600 dark:text-gray-300 hover:text-indigo-600">
                        <i class="fas fa-shopping-cart text-xl"></i>
                        @php $cartCount = \App\Models\Cart::where('user_id', auth()->id())->count() @endphp
                        @if($cartCount > 0)
                            <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">{{ $cartCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('wishlist.index') }}" class="text-gray-600 dark:text-gray-300 hover:text-indigo-600">
                        <i class="fas fa-heart text-xl"></i>
                    </a>
                    <a href="{{ route('borrowings.index') }}" class="text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-indigo-600">Pinjaman Saya</a>
                @else
                    <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-indigo-600 hover:underline">Dashboard Admin</a>
                @endif

                {{-- Dark Mode --}}
                <button @click="darkMode = !darkMode" class="text-gray-500 dark:text-gray-300 hover:text-indigo-600">
                    <i class="fas" :class="darkMode ? 'fa-sun' : 'fa-moon'"></i>
                </button>

                {{-- User Dropdown --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-200">
                        <div class="w-8 h-8 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <i class="fas fa-chevron-down text-xs"></i>
                    </button>
                    <div x-show="open" @click.away="open = false"
                        class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-xl shadow-lg border dark:border-gray-700 py-2 z-50">
                        <p class="px-4 py-2 text-xs text-gray-500 dark:text-gray-400">{{ auth()->user()->name }}</p>
                        <hr class="dark:border-gray-700">
                        @if(auth()->user()->role === 'user')
                            <a href="{{ route('fines.index') }}" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">Denda Saya</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-gray-100 dark:hover:bg-gray-700">Logout</button>
                        </form>
                    </div>
                </div>
            @else
                <button @click="darkMode = !darkMode" class="text-gray-500 dark:text-gray-300">
                    <i class="fas" :class="darkMode ? 'fa-sun' : 'fa-moon'"></i>
                </button>
                <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-indigo-600">Login</a>
                <a href="{{ route('register') }}" class="bg-indigo-600 text-white text-sm px-4 py-2 rounded-full hover:bg-indigo-700">Daftar</a>
            @endauth
        </div>
    </div>
</nav>

{{-- FLASH MESSAGES --}}
@if(session('success') || session('error'))
<div class="max-w-7xl mx-auto px-4 mt-4">
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
            class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl flex items-center justify-between">
            <span><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</span>
            <button @click="show = false"><i class="fas fa-times"></i></button>
        </div>
    @endif
    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
            class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl flex items-center justify-between">
            <span><i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}</span>
            <button @click="show = false"><i class="fas fa-times"></i></button>
        </div>
    @endif
</div>
@endif

{{-- CONTENT --}}
<main class="flex-1">
    @yield('content')
</main>

{{-- FOOTER --}}
<footer class="bg-white dark:bg-gray-800 border-t dark:border-gray-700 mt-12 py-8">
    <div class="max-w-7xl mx-auto px-4 text-center text-sm text-gray-500 dark:text-gray-400">
        <p class="font-semibold text-indigo-600 dark:text-indigo-400 text-lg mb-1">BookLending</p>
        <p>© {{ date('Y') }} Sistem Informasi Peminjaman Buku. All rights reserved.</p>
    </div>
</footer>

</body>
</html>