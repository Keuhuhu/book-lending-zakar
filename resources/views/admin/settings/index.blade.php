@extends('layouts.admin')
@section('title', 'Pengaturan Sistem')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-8">
        <h2 class="text-xl font-bold mb-6 dark:text-white">Pengaturan Denda & Peminjaman</h2>
        <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Denda Per Hari (Rp)
                </label>
                <input type="number" name="daily_fine_amount"
                    value="{{ $settings['daily_fine_amount']->value ?? 2000 }}"
                    class="w-full border dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                <p class="text-xs text-gray-400 mt-1">{{ $settings['daily_fine_amount']->description ?? '' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Maksimal Hari Peminjaman
                </label>
                <input type="number" name="max_borrow_days"
                    value="{{ $settings['max_borrow_days']->value ?? 7 }}"
                    class="w-full border dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                <p class="text-xs text-gray-400 mt-1">{{ $settings['max_borrow_days']->description ?? '' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Auto Cancel Setelah (Jam)
                </label>
                <input type="number" name="auto_cancel_hours"
                    value="{{ $settings['auto_cancel_hours']->value ?? 24 }}"
                    class="w-full border dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none">
                <p class="text-xs text-gray-400 mt-1">{{ $settings['auto_cancel_hours']->description ?? '' }}</p>
            </div>
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2.5 rounded-xl hover:bg-indigo-700 font-semibold">
                <i class="fas fa-save mr-2"></i>Simpan Pengaturan
            </button>
        </form>
    </div>
</div>
@endsection