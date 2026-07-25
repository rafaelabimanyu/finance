@extends('layouts.app')

@section('title', 'Data Payroll')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold text-slate-100">Manajemen Payroll</h3>
            <p class="text-sm text-slate-400">Kelola dan pantau pengeluaran gaji karyawan, teknisi, dan staf PKL harian/bulanan.</p>
        </div>
        <a href="{{ route('payrolls.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-gradient-to-r from-cyan-500 to-blue-600 text-white text-sm font-bold shadow-lg shadow-cyan-500/10 hover:shadow-cyan-500/20 hover:brightness-110 transition duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Catat Payroll Baru
        </a>
    </div>

    <!-- Table Card -->
    <div class="bg-slate-950/40 border border-slate-800 rounded-2xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-800 bg-slate-950/80 text-xs font-semibold text-slate-400 uppercase tracking-wider">
                        <th class="px-6 py-4">Nama Karyawan</th>
                        <th class="px-6 py-4">Jabatan / Role</th>
                        <th class="px-6 py-4">Gaji Pokok</th>
                        <th class="px-6 py-4">Periode</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60 text-sm">
                    @forelse ($payrolls as $payroll)
                        <tr class="hover:bg-slate-900/35 transition duration-150">
                            <td class="px-6 py-4 font-semibold text-slate-200">{{ $payroll->employee_name }}</td>
                            <td class="px-6 py-4 text-slate-400 font-medium">{{ $payroll->role_type }}</td>
                            <td class="px-6 py-4 font-mono font-bold text-slate-200">Rp {{ number_format($payroll->base_salary, 2, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-800 text-slate-300 border border-slate-700">
                                    {{ $payroll->period }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $payroll->status === 'paid' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-amber-500/10 text-amber-400 border border-amber-500/20' }}">
                                    {{ $payroll->status === 'paid' ? 'Lunas (Paid)' : 'Tertunda (Pending)' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="inline-flex items-center gap-3">
                                    <a href="{{ route('payrolls.edit', $payroll) }}" class="text-slate-400 hover:text-cyan-400 transition duration-150">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form action="{{ route('payrolls.destroy', $payroll) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan payroll ini?')" class="inline">
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
                            <td colspan="6" class="px-6 py-10 text-center text-slate-500">
                                Belum ada catatan payroll terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if ($payrolls->hasPages())
            <div class="px-6 py-4 border-t border-slate-800 bg-slate-950/30">
                {{ $payrolls->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
