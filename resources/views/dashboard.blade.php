@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-8">
    <!-- Welcome Header -->
    <div class="bg-gradient-to-r from-slate-900 via-slate-950 to-slate-900 border border-slate-800 p-8 rounded-3xl relative overflow-hidden shadow-2xl">
        <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-bl from-cyan-500/10 to-blue-600/0 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative space-y-2">
            <span class="text-cyan-400 text-xs font-bold tracking-wider uppercase">Selamat Datang Kembali</span>
            <h3 class="text-2xl font-bold text-slate-100">{{ auth()->user()->name }}</h3>
            <p class="text-slate-400 text-sm max-w-2xl">
                Ini adalah dashboard panel admin keuangan dan operasional internal J&J Group. Anda dapat memantau kategori anggaran, data penggajian (*payroll*), serta manajemen akun staf di sini.
            </p>
        </div>
    </div>

    <!-- Date Range Filter Card -->
    <div class="bg-slate-950/20 border border-slate-800 p-6 rounded-2xl">
        <form method="GET" action="{{ route('dashboard') }}" class="flex flex-col sm:flex-row sm:items-end gap-4">
            <div class="space-y-1 flex-1">
                <label for="start_date" class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Mulai Tanggal</label>
                <input id="start_date" type="date" name="start_date" value="{{ $startDate }}" class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-800 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 text-sm text-slate-200 outline-none transition">
            </div>
            <div class="space-y-1 flex-1">
                <label for="end_date" class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Sampai Tanggal</label>
                <input id="end_date" type="date" name="end_date" value="{{ $endDate }}" class="w-full px-4 py-2.5 rounded-xl bg-slate-900 border border-slate-800 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 text-sm text-slate-200 outline-none transition">
            </div>
            <div class="flex gap-2">
                <a href="{{ route('dashboard') }}" class="px-5 py-2.5 text-sm rounded-xl border border-slate-800 hover:bg-slate-900 text-slate-400 font-semibold transition flex items-center justify-center">
                    Reset
                </a>
                <button type="submit" class="px-6 py-2.5 text-sm rounded-xl bg-cyan-500 hover:bg-cyan-600 text-white font-bold transition shadow-lg shadow-cyan-500/10 flex items-center justify-center">
                    Filter Laporan
                </button>
            </div>
        </form>
    </div>

    <!-- Stat Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Card: Omset Kotor -->
        <div class="bg-slate-950/40 border border-slate-800/80 p-6 rounded-2xl shadow-lg relative overflow-hidden group hover:border-slate-700 transition duration-200">
            <div class="flex items-center justify-between mb-4">
                <span class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Total Omset Kotor</span>
                <div class="p-2.5 rounded-xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <h4 class="text-2xl font-extrabold text-slate-100 mb-1">Rp {{ number_format($totalIncome, 2, ',', '.') }}</h4>
            <p class="text-xs text-slate-500">Pendapatan masuk (Eco-Plumbing)</p>
        </div>

        <!-- Card: Total Pengeluaran (Aggregated based on role) -->
        <div class="bg-slate-950/40 border border-slate-800/80 p-6 rounded-2xl shadow-lg relative overflow-hidden group hover:border-slate-700 transition duration-200">
            <div class="flex items-center justify-between mb-4">
                <span class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Total Pengeluaran</span>
                <div class="p-2.5 rounded-xl bg-rose-500/10 text-rose-400 border border-rose-500/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
            </div>
            @if(auth()->user()->role === 'owner')
                <h4 class="text-2xl font-extrabold text-slate-100 mb-1">Rp {{ number_format($totalExpense, 2, ',', '.') }}</h4>
                <p class="text-xs text-slate-500">OPEX (Rp {{ number_format($totalExpenseTransactions, 2, ',', '.') }}) + Payroll (Rp {{ number_format($totalPayrollExpense, 2, ',', '.') }})</p>
            @else
                <h4 class="text-2xl font-extrabold text-slate-100 mb-1">Rp {{ number_format($totalExpenseTransactions, 2, ',', '.') }}</h4>
                <p class="text-xs text-slate-500">Total pengeluaran transaksi operasional (OPEX harian)</p>
            @endif
        </div>

        <!-- Card: Laba Bersih (Owner Only Lock Screen for Admin) -->
        <div class="bg-slate-950/40 border border-slate-800/80 p-6 rounded-2xl shadow-lg relative overflow-hidden group hover:border-slate-700 transition duration-200">
            @if(auth()->user()->role === 'owner')
                <div class="flex items-center justify-between mb-4">
                    <span class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Pendapatan Bersih (Laba/Rugi)</span>
                    <div class="p-2.5 rounded-xl {{ $netProfit >= 0 ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z"/></svg>
                    </div>
                </div>
                <h4 class="text-2xl font-extrabold mb-1 {{ $netProfit >= 0 ? 'text-cyan-400' : 'text-rose-400' }}">
                    Rp {{ number_format($netProfit, 2, ',', '.') }}
                </h4>
                <p class="text-xs text-slate-500">Pendapatan bersih terhitung otomatis</p>
            @else
                <div class="flex items-center justify-between mb-4 opacity-50">
                    <span class="text-slate-550 text-xs font-semibold uppercase tracking-wider">Pendapatan Bersih (Laba/Rugi)</span>
                    <div class="p-2.5 rounded-xl bg-slate-950/60 text-slate-550">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                </div>
                <!-- Blurred Lock state -->
                <div class="filter blur-[3px] select-none">
                    <h4 class="text-2xl font-extrabold text-slate-650 mb-1">Rp 99.999.999</h4>
                </div>
                <p class="text-xs text-rose-455 text-rose-400 font-medium mt-1 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Akses dibatasi (Owner Only)
                </p>
            @endif
        </div>
    </div>

    <!-- Secondary Stat / Info Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Categories count -->
        <div class="bg-slate-950/30 border border-slate-800 p-6 rounded-2xl flex items-center justify-between">
            <div>
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Kategori Aktif</p>
                <h5 class="text-2xl font-bold text-slate-100 mt-1">{{ $totalCategories }}</h5>
            </div>
            <div class="p-3 rounded-xl bg-slate-900 border border-slate-800 text-slate-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            </div>
        </div>

        <!-- Payroll count in period -->
        <div class="bg-slate-950/30 border border-slate-800 p-6 rounded-2xl flex items-center justify-between">
            <div>
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Payroll Periode Ini</p>
                <h5 class="text-2xl font-bold text-slate-100 mt-1">{{ $totalPayrollsCount }}</h5>
            </div>
            <div class="p-3 rounded-xl bg-slate-900 border border-slate-800 text-slate-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
        </div>

        <!-- Users count -->
        <div class="bg-slate-950/30 border border-slate-800 p-6 rounded-2xl flex items-center justify-between">
            <div>
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Pengguna Terdaftar</p>
                <h5 class="text-2xl font-bold text-slate-100 mt-1">{{ $totalUsers }}</h5>
            </div>
            <div class="p-3 rounded-xl bg-slate-900 border border-slate-800 text-slate-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
        </div>
    </div>

    <!-- Quick Links / Shortcuts -->
    <div class="bg-slate-950/20 border border-slate-800/60 p-6 rounded-2xl">
        <h4 class="text-sm font-bold text-slate-200 mb-4">Pintasan Cepat Manajemen Data</h4>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('transactions.create') }}" class="flex items-center justify-between p-4 rounded-xl border border-slate-800 bg-slate-950/60 hover:bg-slate-900 hover:border-cyan-500/30 transition duration-200">
                <span class="text-sm text-slate-300">Catat Transaksi Baru</span>
                <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            </a>
            <a href="{{ route('payrolls.create') }}" class="flex items-center justify-between p-4 rounded-xl border border-slate-800 bg-slate-950/60 hover:bg-slate-900 hover:border-blue-500/30 transition duration-200">
                <span class="text-sm text-slate-300">Catat Payroll Baru</span>
                <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            </a>
            @if(auth()->user()->role === 'owner')
            <a href="{{ route('users.create') }}" class="flex items-center justify-between p-4 rounded-xl border border-slate-800 bg-slate-950/60 hover:bg-slate-900 hover:border-emerald-500/30 transition duration-200">
                <span class="text-sm text-slate-300">Daftarkan Admin Baru</span>
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            </a>
            @endif
        </div>
    </div>
</div>
@endsection
