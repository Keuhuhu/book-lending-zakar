@extends('layouts.admin')
@section('title', 'Manajemen Kategori')

@section('content')
<div class="grid md:grid-cols-2 gap-6">
    {{-- Form Tambah --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6">
        <h2 class="text-lg font-bold mb-4 dark:text-white">Tambah Kategori</h2>
        <form action="{{ route('admin.categories.store') }}" method="POST" class="flex gap-3">
            @csrf
            <input type="text" name="name" placeholder="Nama kategori..." required
                class="flex-1 border dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
            <button class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl hover:bg-indigo-700 font-semibold">
                <i class="fas fa-plus mr-1"></i>Tambah
            </button>
        </form>
    </div>

    {{-- List --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-300">
                <tr>
                    <th class="px-6 py-4 text-left">Kategori</th>
                    <th class="px-6 py-4 text-center">Jumlah Buku</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y dark:divide-gray-700">
                @foreach($categories as $cat)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-6 py-4 font-medium dark:text-white">{{ $cat->name }}</td>
                    <td class="px-6 py-4 text-center text-gray-500">{{ $cat->books_count }}</td>
                    <td class="px-6 py-4 text-center">
                        <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                            @csrf @method('DELETE')
                            <button class="text-red-500 hover:text-red-700 text-xs px-3 py-1 bg-red-50 dark:bg-red-900 rounded-lg">
                                <i class="fas fa-trash mr-1"></i>Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection