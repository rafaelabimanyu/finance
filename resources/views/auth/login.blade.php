<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>J&J Group Finance - Masuk</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-950 text-slate-100 font-sans min-h-screen flex items-center justify-center p-6 antialiased bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-slate-900 via-slate-950 to-black">

    <div class="w-full max-w-md">
        <!-- Logo Branding -->
        <div class="flex flex-col items-center justify-center gap-2 mb-8">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-cyan-500 to-blue-600 flex items-center justify-center font-bold text-2xl text-white shadow-xl shadow-cyan-500/25">
                J&J
            </div>
            <h1 class="text-2xl font-bold bg-gradient-to-r from-slate-100 to-slate-400 bg-clip-text text-transparent">J&J Group Finance</h1>
            <p class="text-slate-500 text-sm text-center">Enterprise Financial & Operational Ledger</p>
        </div>

        <!-- Login Card -->
        <div class="bg-slate-900/50 backdrop-blur-xl border border-slate-800 p-8 rounded-3xl shadow-2xl relative overflow-hidden">
            <!-- Decorative gradient blur -->
            <div class="absolute top-0 right-0 w-32 h-32 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 w-32 h-32 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <form method="POST" action="{{ route('login') }}" class="space-y-6 relative">
                @csrf

                <!-- Error notifications -->
                @if ($errors->any())
                    <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-xs space-y-1">
                        @foreach ($errors->all() as $error)
                            <p class="flex items-center gap-2">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                {{ $error }}
                            </p>
                        @endforeach
                    </div>
                @endif

                <!-- Email field -->
                <div class="space-y-2">
                    <label for="email" class="text-xs font-semibold text-slate-300 uppercase tracking-wider">Email Karyawan</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                        </span>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="nama@jj-group.id" class="w-full pl-11 pr-4 py-3 rounded-2xl bg-slate-950/60 border border-slate-800 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 text-sm placeholder-slate-650 transition duration-200 outline-none text-slate-200">
                    </div>
                </div>

                <!-- Password field -->
                <div class="space-y-2">
                    <div class="flex justify-between items-center">
                        <label for="password" class="text-xs font-semibold text-slate-300 uppercase tracking-wider">Kata Sandi</label>
                    </div>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </span>
                        <input id="password" type="password" name="password" required placeholder="••••••••" class="w-full pl-11 pr-4 py-3 rounded-2xl bg-slate-950/60 border border-slate-800 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 text-sm placeholder-slate-650 transition duration-200 outline-none text-slate-200">
                    </div>
                </div>

                <!-- Remember Me Checkbox -->
                <div class="flex items-center">
                    <input id="remember" type="checkbox" name="remember" class="w-4 h-4 rounded bg-slate-950 border-slate-800 text-cyan-600 focus:ring-cyan-500 focus:ring-offset-slate-900 focus:ring-1 transition duration-200">
                    <label for="remember" class="ml-2 text-sm text-slate-400 font-medium select-none cursor-pointer">Ingat perangkat ini</label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-3.5 px-4 rounded-2xl bg-gradient-to-r from-cyan-500 to-blue-600 text-white text-sm font-bold shadow-lg shadow-cyan-500/10 hover:shadow-cyan-500/20 hover:brightness-110 active:scale-[0.98] transition duration-200">
                    Masuk Ke Panel
                </button>
            </form>
        </div>

        <div class="mt-8 text-center text-xs text-slate-600 font-medium">
            &copy; 2026 J&J Group. All rights reserved.
        </div>
    </div>

</body>
</html>
