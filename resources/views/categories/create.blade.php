@extends('layouts.app')

@section('title', 'Tambah Kategori')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('categories.index') }}" class="p-2 rounded-xl bg-slate-950 border border-slate-800 hover:bg-slate-900 hover:text-slate-200 transition duration-150">
            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h3 class="text-xl font-bold text-slate-100">Tambah Kategori Baru</h3>
            <p class="text-sm text-slate-400">Buat klasifikasi transaksi keuangan baru untuk pembukuan.</p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-slate-950/40 border border-slate-800 rounded-3xl p-6 md:p-8 shadow-2xl relative overflow-hidden">
        <form method="POST" action="{{ route('categories.store') }}" class="space-y-6">
            @csrf

            <!-- Category Name -->
            <div class="space-y-2">
                <label for="name" class="text-xs font-semibold text-slate-300 uppercase tracking-wider">Nama Kategori</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required placeholder="Misal: Eco-Plumbing Service, Biaya Listrik & Air" class="w-full px-4 py-3 rounded-2xl bg-slate-900 border border-slate-800 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 text-sm placeholder-slate-650 transition duration-200 outline-none text-slate-200">
                @error('name')
                    <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category Type -->
            <div class="space-y-2">
                <label for="type" class="text-xs font-semibold text-slate-300 uppercase tracking-wider">Tipe Kategori</label>
                <select id="type" name="type" required class="w-full px-4 py-3 rounded-2xl bg-slate-900 border border-slate-800 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 text-sm transition duration-200 outline-none text-slate-200 cursor-pointer">
                    <option value="" disabled selected>Pilih tipe kategori...</option>
                    <option value="income" {{ old('type') === 'income' ? 'selected' : '' }}>Pemasukan (Income)</option>
                    <option value="expense" {{ old('type') === 'expense' ? 'selected' : '' }}>Pengeluaran (Expense)</option>
                </select>
                @error('type')
                    <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end gap-4 pt-4 border-t border-slate-800/80">
                <a href="{{ route('categories.index') }}" class="px-5 py-3 rounded-2xl border border-slate-800 hover:bg-slate-900 text-slate-400 hover:text-slate-200 text-sm font-semibold transition duration-150">
                    Batal
                </a>
                <button type="submit" class="px-6 py-3 rounded-2xl bg-gradient-to-r from-cyan-500 to-blue-600 text-white text-sm font-bold shadow-lg shadow-cyan-500/10 hover:shadow-cyan-500/20 hover:brightness-110 active:scale-[0.98] transition duration-200">
                    Simpan Kategori
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
