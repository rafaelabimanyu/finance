@extends('layouts.app')

@section('title', 'Ubah Payroll')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('payrolls.index') }}" class="p-2 rounded-xl bg-slate-950 border border-slate-800 hover:bg-slate-900 hover:text-slate-200 transition duration-150">
            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h3 class="text-xl font-bold text-slate-100">Ubah Catatan Payroll</h3>
            <p class="text-sm text-slate-400">Perbarui rincian penggajian karyawan atau teknisi lapangan.</p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-slate-950/40 border border-slate-800 rounded-3xl p-6 md:p-8 shadow-2xl relative overflow-hidden">
        <form method="POST" action="{{ route('payrolls.update', $payroll) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Employee Name -->
            <div class="space-y-2">
                <label for="employee_name" class="text-xs font-semibold text-slate-300 uppercase tracking-wider">Nama Lengkap Karyawan</label>
                <input id="employee_name" type="text" name="employee_name" value="{{ old('employee_name', $payroll->employee_name) }}" required placeholder="Misal: Ardy, Abi, Staf PKL" class="w-full pl-4 pr-4 py-3 rounded-2xl bg-slate-900 border border-slate-800 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 text-sm placeholder-slate-650 transition duration-200 outline-none text-slate-200">
                @error('employee_name')
                    <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Role Type / Position -->
            <div class="space-y-2">
                <label for="role_type" class="text-xs font-semibold text-slate-300 uppercase tracking-wider">Jabatan / Jenis Peran</label>
                <input id="role_type" type="text" name="role_type" value="{{ old('role_type', $payroll->role_type) }}" required placeholder="Misal: Gaji Teknisi Lapangan Eco-Plumbing, Gaji Staf Administrasi" class="w-full pl-4 pr-4 py-3 rounded-2xl bg-slate-900 border border-slate-800 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 text-sm placeholder-slate-650 transition duration-200 outline-none text-slate-200">
                @error('role_type')
                    <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Base Salary -->
                <div class="space-y-2">
                    <label for="base_salary" class="text-xs font-semibold text-slate-300 uppercase tracking-wider">Gaji Pokok (IDR)</label>
                    <input id="base_salary" type="number" step="0.01" name="base_salary" value="{{ old('base_salary', $payroll->base_salary) }}" required placeholder="Misal: 5500000" class="w-full pl-4 pr-4 py-3 rounded-2xl bg-slate-900 border border-slate-800 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 text-sm placeholder-slate-650 transition duration-200 outline-none text-slate-200">
                    @error('base_salary')
                        <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Period -->
                <div class="space-y-2">
                    <label for="period" class="text-xs font-semibold text-slate-300 uppercase tracking-wider">Periode Gaji (YYYY-MM)</label>
                    <input id="period" type="text" name="period" value="{{ old('period', $payroll->period) }}" required placeholder="Contoh: 2026-07" class="w-full pl-4 pr-4 py-3 rounded-2xl bg-slate-900 border border-slate-800 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 text-sm placeholder-slate-650 transition duration-200 outline-none text-slate-200">
                    @error('period')
                        <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Status -->
            <div class="space-y-2">
                <label for="status" class="text-xs font-semibold text-slate-300 uppercase tracking-wider">Status Pembayaran</label>
                <select id="status" name="status" required class="w-full pl-4 pr-4 py-3 rounded-2xl bg-slate-900 border border-slate-800 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 text-sm transition duration-200 outline-none text-slate-200 cursor-pointer">
                    <option value="pending" {{ old('status', $payroll->status) === 'pending' ? 'selected' : '' }}>Tertunda (Pending)</option>
                    <option value="paid" {{ old('status', $payroll->status) === 'paid' ? 'selected' : '' }}>Lunas Terbayar (Paid)</option>
                </select>
                @error('status')
                    <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end gap-4 pt-4 border-t border-slate-800/80">
                <a href="{{ route('payrolls.index') }}" class="px-5 py-3 rounded-2xl border border-slate-800 hover:bg-slate-900 text-slate-400 hover:text-slate-200 text-sm font-semibold transition duration-150">
                    Batal
                </a>
                <button type="submit" class="px-6 py-3 rounded-2xl bg-gradient-to-r from-cyan-500 to-blue-600 text-white text-sm font-bold shadow-lg shadow-cyan-500/10 hover:shadow-cyan-500/20 hover:brightness-110 active:scale-[0.98] transition duration-200">
                    Perbarui Catatan Payroll
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
