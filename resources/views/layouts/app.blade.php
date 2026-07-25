<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>J&J Group Finance - @yield('title', 'Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-900 text-slate-100 font-sans min-h-screen flex flex-col md:flex-row antialiased">
    
    <!-- Sidebar -->
    <aside class="w-full md:w-64 bg-slate-950 border-r border-slate-800 flex flex-col justify-between shrink-0 shadow-xl">
        <div>
            <!-- Branding -->
            <div class="p-6 border-b border-slate-800 bg-slate-950/50 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-cyan-500 to-blue-600 flex items-center justify-center font-bold text-lg text-white shadow-lg shadow-cyan-500/20">
                    J&J
                </div>
                <div>
                    <h1 class="font-bold text-slate-100 tracking-wide text-sm">J&J Group</h1>
                    <p class="text-xs text-cyan-400 font-medium">Finance Control</p>
                </div>
            </div>
            
            <!-- Navigation -->
            <nav class="p-4 space-y-1">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition duration-200 text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20' : 'text-slate-400 hover:bg-slate-900 hover:text-slate-200' }}">
                    <!-- Dashboard Icon -->
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"/></svg>
                    Dashboard
                </a>
                
                <a href="{{ route('categories.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition duration-200 text-sm font-medium {{ request()->routeIs('categories.*') ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20' : 'text-slate-400 hover:bg-slate-900 hover:text-slate-200' }}">
                    <!-- Categories Icon -->
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    Kategori Keuangan
                </a>
                
                <a href="{{ route('payrolls.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition duration-200 text-sm font-medium {{ request()->routeIs('payrolls.*') ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20' : 'text-slate-400 hover:bg-slate-900 hover:text-slate-200' }}">
                    <!-- Payroll Icon -->
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Data Payroll
                </a>

                @if(auth()->user()->role === 'owner')
                <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition duration-200 text-sm font-medium {{ request()->routeIs('users.*') ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/20' : 'text-slate-400 hover:bg-slate-900 hover:text-slate-200' }}">
                    <!-- Users Icon -->
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Kelola Admin
                </a>
                @endif
            </nav>
        </div>
        
        <!-- User & Logout Section -->
        <div class="p-4 border-t border-slate-800 bg-slate-950/80">
            <div class="flex items-center justify-between mb-4">
                <div class="truncate pr-2">
                    <p class="text-sm font-semibold text-slate-200 truncate">{{ auth()->user()->name }}</p>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold {{ auth()->user()->role === 'owner' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/25' : 'bg-blue-500/10 text-blue-400 border border-blue-500/25' }}">
                        {{ strtoupper(auth()->user()->role) }}
                    </span>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl border border-slate-800 hover:border-rose-500/30 hover:bg-rose-500/10 text-slate-400 hover:text-rose-400 text-sm font-medium transition duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Keluar Sesi
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <main class="flex-1 flex flex-col min-w-0">
        <!-- Top bar (Mobile Header / Action Area) -->
        <header class="h-16 border-b border-slate-800 bg-slate-950/40 backdrop-blur-md flex items-center justify-between px-6 md:px-8">
            <h2 class="text-lg font-bold bg-gradient-to-r from-slate-100 to-slate-400 bg-clip-text text-transparent">
                Sistem Pusat Keuangan J&J Group
            </h2>
            <div class="text-xs text-slate-400 font-medium">
                {{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
            </div>
        </header>

        <!-- Main Workspace -->
        <div class="flex-1 p-6 md:p-8 overflow-y-auto">
            <!-- Toast Notifications -->
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm flex items-center gap-3 shadow-lg shadow-emerald-500/5">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-sm flex items-center gap-3 shadow-lg shadow-rose-500/5">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>

</body>
</html>
