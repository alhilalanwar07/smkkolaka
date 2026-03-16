@php
    $profil = \App\Models\ProfilSekolah::first();
    $namaSekolah = $profil->nama_sekolah ?? 'SMK Negeri 1 Kolaka';
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin - SMK Negeri 1 Kolaka' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        if (localStorage.getItem('darkMode') === 'true' || (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>
    @if($profil && $profil->favicon_path)
    <link rel="icon" href="{{ Storage::url($profil->favicon_path) }}">
    @endif
</head>
<body class="antialiased font-sans bg-slate-100 dark:bg-slate-950 text-slate-900 dark:text-slate-100" x-data="{ sidebarOpen: true }">
    @php
        $currentUser = auth()->user();
        $isSuperAdmin = $currentUser?->hasRole('admin') ?? false;
        $isPpdbAdmin = $currentUser?->hasRole('ppdb-admin') ?? false;
    @endphp

    {{-- Toast Notification --}}
    <x-admin.toast />

    <div class="min-h-screen flex">
        {{-- Sidebar --}}
        <aside class="fixed inset-y-0 left-0 z-40 flex flex-col bg-slate-900 dark:bg-slate-900 text-white transition-all duration-300"
               :class="sidebarOpen ? 'w-64' : 'w-20'"
               x-cloak>
            {{-- Logo --}}
            <div class="flex items-center gap-3 px-4 h-16 border-b border-slate-700/50">
                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                </div>
                <span class="font-bold text-sm tracking-tight whitespace-nowrap overflow-hidden" x-show="sidebarOpen" x-transition>SMKN 1 KOLAKA</span>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
                @if ($isSuperAdmin)
                    <x-admin.nav-link href="{{ route('admin.dashboard') }}" icon="home" :active="request()->routeIs('admin.dashboard')">Dashboard</x-admin.nav-link>
                    <x-admin.nav-link href="{{ route('admin.profil-sekolah') }}" icon="building" :active="request()->routeIs('admin.profil-sekolah')">Profil Sekolah</x-admin.nav-link>
                    <x-admin.nav-link href="{{ route('admin.pegawai') }}" icon="users" :active="request()->routeIs('admin.pegawai*')">Pegawai</x-admin.nav-link>
                    <x-admin.nav-link href="{{ route('admin.program-keahlian') }}" icon="book" :active="request()->routeIs('admin.program-keahlian*')">Program Keahlian</x-admin.nav-link>
                    <x-admin.nav-link href="{{ route('admin.tefa') }}" icon="shopping-bag" :active="request()->routeIs('admin.tefa*')">TEFA</x-admin.nav-link>
                    <x-admin.nav-link href="{{ route('admin.berita') }}" icon="newspaper" :active="request()->routeIs('admin.berita*')">Berita</x-admin.nav-link>
                    <x-admin.nav-link href="{{ route('admin.pengumuman') }}" icon="megaphone" :active="request()->routeIs('admin.pengumuman*')">Pengumuman</x-admin.nav-link>
                    <x-admin.nav-link href="{{ route('admin.agenda') }}" icon="calendar" :active="request()->routeIs('admin.agenda*')">Agenda</x-admin.nav-link>
                    <x-admin.nav-link href="{{ route('admin.galeri') }}" icon="image" :active="request()->routeIs('admin.galeri*')">Galeri</x-admin.nav-link>
                @endif

                @if ($isSuperAdmin || $isPpdbAdmin)
                    <div class="pt-3 mt-3 border-t border-slate-800/70 space-y-1">
                        <p class="px-3 text-[11px] font-bold uppercase tracking-[0.25em] text-slate-500" x-show="sidebarOpen" x-transition>PPDB</p>
                        <x-admin.nav-link href="{{ route('admin.ppdb') }}" icon="clipboard-list" :active="request()->routeIs('admin.ppdb')">Ringkasan PPDB</x-admin.nav-link>
                        @if ($isSuperAdmin)
                            <x-admin.nav-link href="{{ route('admin.ppdb.analytics') }}" icon="chart-square" :active="request()->routeIs('admin.ppdb.analytics')">Analisa PPDB</x-admin.nav-link>
                        @endif
                        <x-admin.nav-link href="{{ route('admin.ppdb.applicants') }}" icon="document-text" :active="request()->routeIs('admin.ppdb.applicants')">Data Pendaftar</x-admin.nav-link>
                        <x-admin.nav-link href="{{ route('admin.ppdb.tests') }}" icon="clipboard-check" :active="request()->routeIs('admin.ppdb.tests')">Panitia Tes</x-admin.nav-link>
                        <x-admin.nav-link href="{{ route('admin.ppdb.re-registration') }}" icon="beaker" :active="request()->routeIs('admin.ppdb.re-registration')">Verifikasi Daftar Ulang</x-admin.nav-link>
                        <x-admin.nav-link href="{{ route('admin.ppdb.settings') }}" icon="sliders" :active="request()->routeIs('admin.ppdb.settings')">Pengaturan PPDB</x-admin.nav-link>
                    </div>
                @endif

                @if ($isSuperAdmin)
                <div class="pt-3 mt-3 border-t border-slate-800/70 space-y-1">
                    <x-admin.nav-link href="{{ route('admin.settings') }}" icon="cog" :active="request()->routeIs('admin.settings')">Pengaturan</x-admin.nav-link>
                </div>
                @endif
            </nav>

            {{-- User --}}
            <div class="border-t border-slate-700/50 p-3">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-blue-500 rounded-lg flex items-center justify-center text-sm font-bold shrink-0">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="overflow-hidden" x-show="sidebarOpen" x-transition>
                        <p class="text-sm font-semibold truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-slate-400 truncate">{{ auth()->user()->email }}</p>
                        <p class="text-[11px] uppercase tracking-[0.24em] text-blue-300 truncate mt-1">
                            {{ $isSuperAdmin ? 'Super Admin' : 'Admin PPDB' }}
                        </p>
                    </div>
                </div>
            </div>
        </aside>

        {{-- Main Content --}}
        <div class="flex-1 transition-all duration-300" :class="sidebarOpen ? 'ml-64' : 'ml-20'">
            {{-- Top Bar --}}
            <header class="sticky top-0 z-30 bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border-b border-slate-200 dark:border-slate-800 h-16 flex items-center justify-between px-6">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-500 dark:text-slate-400 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <h2 class="text-lg font-bold text-slate-800 dark:text-slate-100">{{ $title ?? 'Dashboard' }}</h2>
                </div>
                <div class="flex items-center gap-3">
                    {{-- Dark Mode Toggle --}}
                    <button
                        x-data="{ dark: document.documentElement.classList.contains('dark') }"
                        @click="dark = !dark; document.documentElement.classList.toggle('dark'); localStorage.setItem('darkMode', dark)"
                        class="p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-500 dark:text-slate-400 transition"
                        title="Toggle Dark Mode">
                        <svg x-show="!dark" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                        <svg x-show="dark" x-cloak xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </button>
                    <a href="/" target="_blank" class="text-sm text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition">Lihat Website</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="px-4 py-2 text-sm font-semibold text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950 rounded-lg transition">Logout</button>
                    </form>
                </div>
            </header>

            {{-- Page Content --}}
            <main class="p-6">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
