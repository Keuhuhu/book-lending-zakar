<!DOCTYPE html>
<html lang="id" x-data="{ darkMode: false, sidebarOpen: true }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - @yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { darkMode: 'class' }</script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar { width: 256px; transition: width 0.3s; }
        .sidebar.collapsed { width: 64px; }
        .main-content { margin-left: 256px; transition: margin-left 0.3s; }
        .main-content.expanded { margin-left: 64px; }
        .sidebar-label { display: inline; }
        .sidebar.collapsed .sidebar-label { display: none; }
    </style>
</head>

<body class="bg-gray-100 dark:bg-gray-900 min-h-screen" x-data="{ darkMode: false, collapsed: false }">

<div style="display:flex; min-height:100vh;">
    {{-- SIDEBAR --}}
    <aside id="sidebar" class="sidebar bg-indigo-900 dark:bg-gray-800 text-white flex flex-col fixed top-0 left-0 h-full z-40"
        :class="{ 'collapsed': collapsed }">

        {{-- Logo --}}
        <div class="flex items-center gap-3 px-4 py-5 border-b border-indigo-800">
            <i class="fas fa-book-open text-xl text-indigo-300 flex-shrink-0"></i>
            <span class="sidebar-label font-bold text-lg">BookLending</span>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 py-4 space-y-1 px-2">
            @php
                $menus = [
                    ['route' => 'admin.dashboard', 'icon' => 'fa-gauge', 'label' => 'Dashboard'],
                    ['route' => 'admin.books.index', 'icon' => 'fa-book', 'label' => 'Buku'],
                    ['route' => 'admin.categories.index', 'icon' => 'fa-tags', 'label' => 'Kategori'],
                    ['route' => 'admin.borrowings.index', 'icon' => 'fa-hand-holding-heart', 'label' => 'Peminjaman'],
                    ['route' => 'admin.fines.index', 'icon' => 'fa-money-bill', 'label' => 'Denda'],
                    ['route' => 'admin.reports.index', 'icon' => 'fa-chart-bar', 'label' => 'Laporan'],
                    ['route' => 'admin.stock-logs.index', 'icon' => 'fa-boxes-stacked', 'label' => 'Log Stok'],
                    ['route' => 'admin.settings.index', 'icon' => 'fa-gear', 'label' => 'Pengaturan'],
                ];
            @endphp

            @foreach($menus as $menu)
            <a href="{{ route($menu['route']) }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all
                {{ request()->routeIs($menu['route']) ? 'bg-indigo-700 text-white' : 'text-indigo-200 hover:bg-indigo-800' }}">
                <i class="fas {{ $menu['icon'] }} w-5 flex-shrink-0 text-center"></i>
                <span class="sidebar-label text-sm font-medium">{{ $menu['label'] }}</span>
            </a>
            @endforeach
        </nav>

        <div class="p-3 border-t border-indigo-800">
            <a href="{{ route('catalog.index') }}" target="_blank"
                class="flex items-center gap-3 px-3 py-2 rounded-xl text-indigo-300 hover:bg-indigo-800 text-sm">
                <i class="fas fa-arrow-up-right-from-square w-5 text-center flex-shrink-0"></i>
                <span class="sidebar-label">Lihat Website</span>
            </a>
        </div>
    </aside>

    {{-- MAIN --}}
    <div id="mainContent" class="main-content flex-1 flex flex-col min-h-screen">

        {{-- TOPBAR --}}
        <header class="bg-white dark:bg-gray-800 shadow-sm px-6 py-4 flex items-center justify-between sticky top-0 z-30">
            <div class="flex items-center gap-4">
                <button onclick="toggleSidebar()" class="text-gray-500 hover:text-indigo-600">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <h1 class="text-lg font-semibold text-gray-700 dark:text-gray-200">@yield('title', 'Dashboard')</h1>
            </div>
            <div class="flex items-center gap-4">
                <button @click="darkMode = !darkMode" class="text-gray-500 hover:text-indigo-600">
                    <i class="fas" :class="darkMode ? 'fa-sun' : 'fa-moon'"></i>
                </button>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold text-sm">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200 hidden md:block">
                        {{ auth()->user()->name }}
                    </span>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-sm text-red-500 hover:text-red-700">
                        <i class="fas fa-right-from-bracket"></i>
                    </button>
                </form>
            </div>
        </header>

        {{-- PAGE CONTENT --}}
        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>
</div>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const main = document.getElementById('mainContent');
    sidebar.classList.toggle('collapsed');
    main.classList.toggle('expanded');
}
</script>

@include('components.toast')
</body>
</html>