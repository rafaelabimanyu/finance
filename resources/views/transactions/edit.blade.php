@extends('layouts.app')

@section('title', 'Ubah Transaksi')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('transactions.index') }}" class="p-2 rounded-xl bg-slate-950 border border-slate-800 hover:bg-slate-900 hover:text-slate-200 transition duration-150">
            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h3 class="text-xl font-bold text-slate-100">Ubah Transaksi</h3>
            <p class="text-sm text-slate-400">Sesuaikan informasi transaksi keuangan harian.</p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-slate-950/40 border border-slate-800 rounded-3xl p-6 md:p-8 shadow-2xl relative overflow-hidden">
        <form method="POST" action="{{ route('transactions.update', $transaction) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Category -->
            <div class="space-y-2">
                <label for="category_id" class="text-xs font-semibold text-slate-300 uppercase tracking-wider">Kategori Anggaran</label>
                <select id="category_id" name="category_id" required class="w-full px-4 py-3 rounded-2xl bg-slate-900 border border-slate-800 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 text-sm transition duration-200 outline-none text-slate-200 cursor-pointer">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" data-type="{{ $cat->type }}" {{ old('category_id', $transaction->category_id) == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }} ({{ $cat->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }})
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Transaction Title -->
            <div class="space-y-2">
                <label for="title" class="text-xs font-semibold text-slate-300 uppercase tracking-wider">Judul / Deskripsi Transaksi</label>
                <input id="title" type="text" name="title" value="{{ old('title', $transaction->title) }}" required placeholder="Misal: Pembayaran Order Layanan Eco-Plumbing - Ardy, Pembelian Token Listrik Kantor" class="w-full px-4 py-3 rounded-2xl bg-slate-900 border border-slate-800 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 text-sm placeholder-slate-650 transition duration-200 outline-none text-slate-200">
                @error('title')
                    <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Amount -->
                <div class="space-y-2">
                    <label for="amount" class="text-xs font-semibold text-slate-300 uppercase tracking-wider">Nominal Transaksi (Rp)</label>
                    <input id="amount" type="number" step="0.01" min="0.01" name="amount" value="{{ old('amount', $transaction->amount) }}" required placeholder="Misal: 1500000" class="w-full px-4 py-3 rounded-2xl bg-slate-900 border border-slate-800 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 text-sm placeholder-slate-655 transition duration-200 outline-none text-slate-200">
                    @error('amount')
                        <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Transaction Date -->
                <div class="space-y-2">
                    <label for="transaction_date" class="text-xs font-semibold text-slate-300 uppercase tracking-wider">Tanggal Transaksi</label>
                    <input id="transaction_date" type="date" name="transaction_date" value="{{ old('transaction_date', $transaction->transaction_date->toDateString()) }}" required class="w-full px-4 py-3 rounded-2xl bg-slate-900 border border-slate-800 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 text-sm transition duration-200 outline-none text-slate-200">
                    @error('transaction_date')
                        <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Transaction Type -->
            <div class="space-y-2">
                <label for="type" class="text-xs font-semibold text-slate-300 uppercase tracking-wider">Tipe Transaksi</label>
                <select id="type" name="type" required class="w-full px-4 py-3 rounded-2xl bg-slate-900 border border-slate-800 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 text-sm transition duration-200 outline-none text-slate-200 cursor-pointer pointer-events-none bg-slate-950/60">
                    <option value="income" {{ old('type', $transaction->type) === 'income' ? 'selected' : '' }}>Pemasukan (Income)</option>
                    <option value="expense" {{ old('type', $transaction->type) === 'expense' ? 'selected' : '' }}>Pengeluaran (Expense)</option>
                </select>
                <span class="text-[10px] text-slate-400 block mt-1">Tipe ini secara otomatis mengikuti kategori anggaran yang Anda pilih demi keakuratan.</span>
                @error('type')
                    <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end gap-4 pt-4 border-t border-slate-800/80">
                <a href="{{ route('transactions.index') }}" class="px-5 py-3 rounded-2xl border border-slate-800 hover:bg-slate-900 text-slate-400 hover:text-slate-200 text-sm font-semibold transition duration-150">
                    Batal
                </a>
                <button type="submit" class="px-6 py-3 rounded-2xl bg-gradient-to-r from-cyan-500 to-blue-600 text-white text-sm font-bold shadow-lg shadow-cyan-500/10 hover:shadow-cyan-500/20 hover:brightness-110 active:scale-[0.98] transition duration-200">
                    Perbarui Transaksi
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const categorySelect = document.getElementById('category_id');
        const typeSelect = document.getElementById('type');

        function updateType() {
            const selectedOption = categorySelect.options[categorySelect.selectedIndex];
            const type = selectedOption.getAttribute('data-type');
            if (type) {
                typeSelect.value = type;
            }
        }

        categorySelect.addEventListener('change', updateType);
        if (categorySelect.value) {
            updateType();
        }
    });
</script>
@endsection
