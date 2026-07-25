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

    <!-- Stat Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card: Categories -->
        <div class="bg-slate-950/40 border border-slate-800/80 p-6 rounded-2xl shadow-lg relative overflow-hidden group hover:border-slate-700 transition duration-200">
            <div class="flex items-center justify-between mb-4">
                <span class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Kategori Keuangan</span>
                <div class="p-2.5 rounded-xl bg-cyan-500/10 text-cyan-400 border border-cyan-500/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
            </div>
            <h4 class="text-3xl font-extrabold text-slate-100 mb-1">{{ $totalCategories }}</h4>
            <p class="text-xs text-slate-500">Kategori pemasukan & pengeluaran</p>
        </div>

        <!-- Card: Total Payrolls -->
        <div class="bg-slate-950/40 border border-slate-800/80 p-6 rounded-2xl shadow-lg relative overflow-hidden group hover:border-slate-700 transition duration-200">
            <div class="flex items-center justify-between mb-4">
                <span class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Total Payroll</span>
                <div class="p-2.5 rounded-xl bg-blue-500/10 text-blue-400 border border-blue-500/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
            </div>
            <h4 class="text-3xl font-extrabold text-slate-100 mb-1">{{ $totalPayrolls }}</h4>
            <p class="text-xs text-slate-500">Catatan penggajian terdaftar</p>
        </div>

        <!-- Card: Gaji Dibayar -->
        <div class="bg-slate-950/40 border border-slate-800/80 p-6 rounded-2xl shadow-lg relative overflow-hidden group hover:border-slate-700 transition duration-200">
            <div class="flex items-center justify-between mb-4">
                <span class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Gaji Terbayar</span>
                <div class="p-2.5 rounded-xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 8h6m-6 2h6m-6 2h6m-2-8H8a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8"/></svg>
                </div>
            </div>
            <h4 class="text-xl font-extrabold text-emerald-400 mb-1">Rp {{ number_format($totalSalaryPaid, 2, ',', '.') }}</h4>
            <p class="text-xs text-slate-500">Total payroll status paid</p>
        </div>

        <!-- Card: Gaji Pending -->
        <div class="bg-slate-950/40 border border-slate-800/80 p-6 rounded-2xl shadow-lg relative overflow-hidden group hover:border-slate-700 transition duration-200">
            <div class="flex items-center justify-between mb-4">
                <span class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Gaji Tertunda</span>
                <div class="p-2.5 rounded-xl bg-amber-500/10 text-amber-400 border border-amber-500/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <h4 class="text-xl font-extrabold text-amber-400 mb-1">Rp {{ number_format($totalSalaryPending, 2, ',', '.') }}</h4>
            <p class="text-xs text-slate-500">Total payroll status pending</p>
        </div>
    </div>

    <!-- Quick Links / Shortcuts -->
    <div class="bg-slate-950/20 border border-slate-800/60 p-6 rounded-2xl">
        <h4 class="text-sm font-bold text-slate-200 mb-4">Pintasan Cepat Manajemen Data</h4>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('categories.create') }}" class="flex items-center justify-between p-4 rounded-xl border border-slate-800 bg-slate-950/60 hover:bg-slate-900 hover:border-cyan-500/30 transition duration-200">
                <span class="text-sm text-slate-300">Buat Kategori Baru</span>
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
