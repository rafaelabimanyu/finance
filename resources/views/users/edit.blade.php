@extends('layouts.app')

@section('title', 'Ubah Pengguna')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('users.index') }}" class="p-2 rounded-xl bg-slate-950 border border-slate-800 hover:bg-slate-900 hover:text-slate-200 transition duration-150">
            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h3 class="text-xl font-bold text-slate-100">Ubah Data Pengguna</h3>
            <p class="text-sm text-slate-400">Sesuaikan informasi profil atau ubah hak akses pengguna.</p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-slate-950/40 border border-slate-800 rounded-3xl p-6 md:p-8 shadow-2xl relative overflow-hidden">
        <form method="POST" action="{{ route('users.update', $user) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div class="space-y-2">
                <label for="name" class="text-xs font-semibold text-slate-300 uppercase tracking-wider">Nama Lengkap</label>
                <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required placeholder="Misal: Ardy Wijaya" class="w-full px-4 py-3 rounded-2xl bg-slate-900 border border-slate-800 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 text-sm placeholder-slate-650 transition duration-200 outline-none text-slate-200">
                @error('name')
                    <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div class="space-y-2">
                <label for="email" class="text-xs font-semibold text-slate-300 uppercase tracking-wider">Alamat Email</label>
                <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required placeholder="nama@jj-group.id" class="w-full px-4 py-3 rounded-2xl bg-slate-900 border border-slate-800 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 text-sm placeholder-slate-650 transition duration-200 outline-none text-slate-200">
                @error('email')
                    <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Role Selection -->
            <div class="space-y-2">
                <label for="role" class="text-xs font-semibold text-slate-300 uppercase tracking-wider">Role Hak Akses</label>
                <select id="role" name="role" required class="w-full px-4 py-3 rounded-2xl bg-slate-900 border border-slate-800 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 text-sm transition duration-200 outline-none text-slate-200 cursor-pointer">
                    <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin (Pengelola Data Harian)</option>
                    <option value="owner" {{ old('role', $user->role) === 'owner' ? 'selected' : '' }}>Owner (Pemilik Sistem)</option>
                </select>
                @error('role')
                    <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Info Alert -->
            <div class="p-4 rounded-xl bg-slate-950/80 border border-slate-800 text-xs text-slate-400">
                <span class="font-bold text-slate-300 block mb-1">Catatan Keamanan</span>
                Kosongkan kolom kata sandi di bawah jika Anda tidak berniat mengubah kata sandi pengguna ini.
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Password -->
                <div class="space-y-2">
                    <label for="password" class="text-xs font-semibold text-slate-300 uppercase tracking-wider">Kata Sandi Baru</label>
                    <input id="password" type="password" name="password" placeholder="Minimal 8 karakter" class="w-full px-4 py-3 rounded-2xl bg-slate-900 border border-slate-800 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 text-sm placeholder-slate-650 transition duration-200 outline-none text-slate-200">
                    @error('password')
                        <p class="text-xs text-rose-400 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Confirmation -->
                <div class="space-y-2">
                    <label for="password_confirmation" class="text-xs font-semibold text-slate-300 uppercase tracking-wider">Konfirmasi Kata Sandi Baru</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Ulangi kata sandi baru" class="w-full px-4 py-3 rounded-2xl bg-slate-900 border border-slate-800 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 text-sm placeholder-slate-650 transition duration-200 outline-none text-slate-200">
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end gap-4 pt-4 border-t border-slate-800/80">
                <a href="{{ route('users.index') }}" class="px-5 py-3 rounded-2xl border border-slate-800 hover:bg-slate-900 text-slate-400 hover:text-slate-200 text-sm font-semibold transition duration-150">
                    Batal
                </a>
                <button type="submit" class="px-6 py-3 rounded-2xl bg-gradient-to-r from-cyan-500 to-blue-600 text-white text-sm font-bold shadow-lg shadow-cyan-500/10 hover:shadow-cyan-500/20 hover:brightness-110 active:scale-[0.98] transition duration-200">
                    Perbarui Pengguna
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
