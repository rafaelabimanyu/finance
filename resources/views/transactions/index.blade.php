@extends('layouts.app')

@section('title', 'Transaksi Keuangan')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold text-slate-100">Buku Transaksi Harian</h3>
            <p class="text-sm text-slate-400">Pencatatan detail arus pemasukan dan pengeluaran operasional secara real-time.</p>
        </div>
        <a href="{{ route('transactions.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gradient-to-r from-cyan-500 to-blue-600 text-white text-sm font-bold shadow-lg shadow-cyan-500/10 hover:shadow-cyan-500/20 hover:brightness-110 transition duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Catat Transaksi
        </a>
    </div>

    <!-- Filter Card -->
    <div class="bg-slate-950/20 border border-slate-800 p-6 rounded-2xl">
        <form method="GET" action="{{ route('transactions.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4">
            <!-- Search Keyword -->
            <div class="space-y-1">
                <label for="search" class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Cari Judul</label>
                <input id="search" type="text" name="search" value="{{ request('search') }}" placeholder="Cari..." class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-800 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 text-xs text-slate-200 outline-none placeholder-slate-650 transition">
            </div>

            <!-- Filter Type -->
            <div class="space-y-1">
                <label for="type" class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Tipe</label>
                <select id="type" name="type" class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-800 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 text-xs text-slate-200 outline-none cursor-pointer">
                    <option value="">Semua Tipe</option>
                    <option value="income" {{ request('type') === 'income' ? 'selected' : '' }}>Pemasukan (Income)</option>
                    <option value="expense" {{ request('type') === 'expense' ? 'selected' : '' }}>Pengeluaran (Expense)</option>
                </select>
            </div>

            <!-- Filter Category -->
            <div class="space-y-1">
                <label for="category_id" class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Kategori</label>
                <select id="category_id" name="category_id" class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-800 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 text-xs text-slate-200 outline-none cursor-pointer">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Start Date -->
            <div class="space-y-1">
                <label for="start_date" class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Mulai Tanggal</label>
                <input id="start_date" type="date" name="start_date" value="{{ request('start_date') }}" class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-800 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 text-xs text-slate-200 outline-none transition">
            </div>

            <!-- End Date -->
            <div class="space-y-1">
                <label for="end_date" class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Sampai Tanggal</label>
                <input id="end_date" type="date" name="end_date" value="{{ request('end_date') }}" class="w-full px-3 py-2 rounded-xl bg-slate-900 border border-slate-800 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 text-xs text-slate-200 outline-none transition">
            </div>

            <!-- Action buttons -->
            <div class="sm:col-span-2 md:col-span-5 flex flex-col sm:flex-row justify-between items-center gap-3 mt-2">
                <div>
                    <a href="{{ route('transactions.export', request()->query()) }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs rounded-xl bg-slate-900 border border-slate-800 hover:border-cyan-500/30 hover:bg-slate-900/60 text-slate-300 hover:text-cyan-400 font-semibold transition w-full sm:w-auto justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Ekspor Laporan (CSV)
                    </a>
                </div>
                <div class="flex gap-3 w-full sm:w-auto justify-end">
                    <a href="{{ route('transactions.index') }}" class="px-4 py-2 text-xs rounded-xl border border-slate-800 hover:bg-slate-900 text-slate-400 font-semibold transition">
                        Reset Filter
                    </a>
                    <button type="submit" class="px-5 py-2 text-xs rounded-xl bg-cyan-500 text-white font-bold hover:bg-cyan-600 transition shadow-lg shadow-cyan-500/10">
                        Terapkan Filter
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Table Card -->
    <div class="bg-slate-950/40 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-800 bg-slate-950/80 text-xs font-semibold text-slate-400 uppercase tracking-wider">
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Judul / Deskripsi</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">Nominal</th>
                        <th class="px-6 py-4">Tipe</th>
                        <th class="px-6 py-4">Pencatat</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 text-sm">
                    @forelse ($transactions as $tx)
                        <tr class="hover:bg-slate-900/35 transition duration-150">
                            <td class="px-6 py-4 text-slate-300 font-medium whitespace-nowrap">
                                {{ $tx->transaction_date->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 font-semibold text-slate-100">
                                {{ $tx->title }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-slate-400 text-xs font-medium">
                                    {{ $tx->category->name }}
                                </span>
                            </td>
                            <td class="px-6 py-4 font-mono font-bold whitespace-nowrap {{ $tx->type === 'income' ? 'text-emerald-400' : 'text-rose-400' }}">
                                {{ $tx->type === 'income' ? '+' : '-' }} Rp {{ number_format($tx->amount, 2, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-semibold {{ $tx->type === 'income' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20' }}">
                                    {{ $tx->type === 'income' ? 'INCOME' : 'EXPENSE' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-400 font-medium">
                                {{ $tx->user->name }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex items-center gap-3">
                                    <a href="{{ route('transactions.edit', $tx) }}" class="text-slate-400 hover:text-cyan-400 transition duration-150">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form action="{{ route('transactions.destroy', $tx) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini? (Soft Delete)')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-slate-400 hover:text-rose-500 transition duration-150">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-slate-500">
                                Belum ada transaksi tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if ($transactions->hasPages())
            <div class="px-6 py-4 border-t border-slate-800 bg-slate-950/30">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
