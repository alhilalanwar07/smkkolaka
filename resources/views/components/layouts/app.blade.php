@php
    $profil = \App\Models\ProfilSekolah::first();
    $namaSekolah = $profil->nama_sekolah ?? 'SMK Negeri 1 Kolaka';
    $sosmed = $profil->tautan_sosmed ?? [];
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? $namaSekolah }}</title>
    <meta name="description" content="{{ $metaDescription ?? 'Website resmi ' . $namaSekolah }}">
    <meta name="keywords" content="{{ $metaKeywords ?? $namaSekolah . ', sekolah kejuruan, berita sekolah, pendidikan vokasi' }}">
    <meta name="robots" content="{{ $metaRobots ?? 'index,follow' }}">
    @if(!empty($canonicalUrl))
    <link rel="canonical" href="{{ $canonicalUrl }}">
    @endif

    <meta property="og:site_name" content="{{ $namaSekolah }}">
    <meta property="og:type" content="{{ $ogType ?? 'website' }}">
    <meta property="og:title" content="{{ $ogTitle ?? ($title ?? $namaSekolah) }}">
    <meta property="og:description" content="{{ $ogDescription ?? ($metaDescription ?? 'Website resmi ' . $namaSekolah) }}">
    <meta property="og:url" content="{{ $ogUrl ?? url()->current() }}">
    <meta property="og:image" content="{{ $ogImage ?? ($profil && $profil->logo_path ? url(Storage::url($profil->logo_path)) : asset('favicon.ico')) }}">

    <meta name="twitter:card" content="{{ $twitterCard ?? 'summary' }}">
    <meta name="twitter:title" content="{{ $twitterTitle ?? ($ogTitle ?? ($title ?? $namaSekolah)) }}">
    <meta name="twitter:description" content="{{ $twitterDescription ?? ($ogDescription ?? ($metaDescription ?? 'Website resmi ' . $namaSekolah)) }}">
    <meta name="twitter:image" content="{{ $twitterImage ?? ($ogImage ?? ($profil && $profil->logo_path ? url(Storage::url($profil->logo_path)) : asset('favicon.ico'))) }}">

    @if(!empty($articlePublishedTime))
    <meta property="article:published_time" content="{{ $articlePublishedTime }}">
    @endif
    @if(!empty($articleModifiedTime))
    <meta property="article:modified_time" content="{{ $articleModifiedTime }}">
    @endif
    @if(!empty($structuredData))
    <script type="application/ld+json">{!! $structuredData !!}</script>
    @endif

    @if($profil && $profil->favicon_path)
    <link rel="icon" href="{{ Storage::url($profil->favicon_path) }}">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased font-sans bg-white text-slate-900 selection:bg-blue-500 selection:text-white" x-data="{ mobileOpen: false, scrolled: false }" @scroll.window="scrolled = window.scrollY > 50">
    <!-- Navigation -->
    <nav class="fixed top-0 inset-x-0 z-50 transition-all duration-500" :class="scrolled ? 'bg-white/90 backdrop-blur-2xl shadow-lg shadow-slate-200/20 border-b border-slate-100' : 'bg-transparent'">
        <!-- Scroll Progress Bar -->
        <div class="absolute bottom-0 left-0 right-0 h-[2px] bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500 scroll-indicator" x-data x-init="window.addEventListener('scroll', () => { $el.style.setProperty('--scroll-progress', (window.scrollY / (document.body.scrollHeight - window.innerHeight)).toFixed(3)) })"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    @if($profil && $profil->logo_path)
                        <img src="{{ Storage::url($profil->logo_path) }}" class="w-11 h-11 rounded-xl object-contain group-hover:scale-110 transition-transform duration-300" alt="{{ $namaSekolah }}">
                    @else
                        <div class="w-11 h-11 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-500/30 group-hover:shadow-blue-500/50 transition-all group-hover:scale-110">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                        </div>
                    @endif
                    <div>
                        <span class="block text-lg font-extrabold tracking-tight leading-none transition-colors" :class="scrolled ? 'text-slate-900' : 'text-slate-900'">SMKN 1 KOLAKA</span>
                        <span class="text-[10px] font-semibold tracking-[0.2em] text-blue-600 uppercase">Center of Excellence</span>
                    </div>
                </a>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center gap-1">
                    @php
                        $currentRoute = request()->route()?->getName();
                        $navItems = [
                            ['route' => 'home', 'label' => 'Beranda'],
                            ['route' => 'profil', 'label' => 'Profil'],
                            ['route' => 'jurusan.index', 'label' => 'Jurusan'],
                            ['route' => 'ppdb.index', 'label' => 'PPDB'],
                            ['route' => 'berita.index', 'label' => 'Berita'],
                            ['route' => 'galeri', 'label' => 'Galeri'],
                            ['route' => 'agenda.index', 'label' => 'Agenda'],
                        ];
                    @endphp
                    @foreach($navItems as $nav)
                        <a href="{{ route($nav['route']) }}" class="relative px-4 py-2 text-sm font-semibold rounded-xl transition-all duration-300 {{ $currentRoute === $nav['route'] ? 'text-blue-600 bg-blue-50/80' : 'text-slate-600 hover:text-blue-600 hover:bg-blue-50/50' }}">
                            {{ $nav['label'] }}
                            @if($currentRoute === $nav['route'])
                            <span class="absolute bottom-0 left-1/2 -translate-x-1/2 w-1 h-1 bg-blue-600 rounded-full"></span>
                            @endif
                        </a>
                    @endforeach
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center">
                    <button @click="mobileOpen = !mobileOpen" class="p-2 text-slate-600 rounded-lg hover:bg-slate-100">
                        <svg x-show="!mobileOpen" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                        <svg x-show="mobileOpen" x-cloak xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileOpen" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 -translate-y-2" class="md:hidden bg-white border-t border-slate-100 shadow-lg" @click.outside="mobileOpen = false">
            <div class="px-4 py-3 space-y-1">
                @foreach($navItems as $nav)
                    <a href="{{ route($nav['route']) }}" class="block px-4 py-3 text-sm font-semibold rounded-lg {{ $currentRoute === $nav['route'] ? 'text-blue-600 bg-blue-50' : 'text-slate-700 hover:bg-slate-50' }}">{{ $nav['label'] }}</a>
                @endforeach
            </div>
        </div>
    </nav>

    <main class="pt-20">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-white pt-20 pb-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-16">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center gap-4 mb-6">
                        @if($profil && $profil->logo_path)
                            <img src="{{ Storage::url($profil->logo_path) }}" class="w-10 h-10 rounded-lg object-contain" alt="{{ $namaSekolah }}">
                        @else
                            <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                            </div>
                        @endif
                        <span class="text-2xl font-bold tracking-tight">SMKN 1 KOLAKA</span>
                    </div>
                    <p class="text-slate-400 max-w-md leading-relaxed mb-6">
                        Mewujudkan lembaga pendidikan kejuruan yang unggul dalam mencetak lulusan profesional, berakhlak mulia, dan siap bersaing di era digital global.
                    </p>
                    @if(!empty($sosmed))
                    <div class="flex gap-3">
                        @foreach(['facebook','instagram','youtube','twitter'] as $platform)
                            @if(!empty($sosmed[$platform]))
                            <a href="{{ $sosmed[$platform] }}" target="_blank" rel="noopener noreferrer" class="w-10 h-10 bg-slate-800 rounded-lg flex items-center justify-center hover:bg-blue-600 transition-colors text-slate-300 hover:text-white">
                                @if($platform === 'facebook')
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
                                @elseif($platform === 'instagram')
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                                @elseif($platform === 'youtube')
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                @elseif($platform === 'twitter')
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                @endif
                            </a>
                            @endif
                        @endforeach
                    </div>
                    @endif
                </div>
                <div>
                    <h4 class="text-lg font-bold mb-6">Tautan Cepat</h4>
                    <ul class="space-y-4 text-slate-400">
                        <li><a href="{{ route('profil') }}" class="hover:text-blue-400 transition-colors">Profil Sekolah</a></li>
                        <li><a href="{{ route('jurusan.index') }}" class="hover:text-blue-400 transition-colors">Program Keahlian</a></li>
                        <li><a href="{{ route('ppdb.index') }}" class="hover:text-blue-400 transition-colors">PPDB Online</a></li>
                        <li><a href="{{ route('ppdb.form') }}" class="hover:text-blue-400 transition-colors">Form Pendaftaran</a></li>
                        <li><a href="{{ route('berita.index') }}" class="hover:text-blue-400 transition-colors">Berita</a></li>
                        <li><a href="{{ route('galeri') }}" class="hover:text-blue-400 transition-colors">Galeri</a></li>
                        <li><a href="{{ route('agenda.index') }}" class="hover:text-blue-400 transition-colors">Agenda</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-bold mb-6">Kontak Kami</h4>
                    <ul class="space-y-4 text-slate-400">
                        @if($profil && $profil->alamat_lengkap)
                        <li class="flex items-start gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            <span>{{ $profil->alamat_lengkap }}</span>
                        </li>
                        @endif
                        @if($profil && $profil->nomor_telepon)
                        <li class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                            <span>{{ $profil->nomor_telepon }}</span>
                        </li>
                        @endif
                        @if($profil && $profil->email_resmi)
                        <li class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            <span>{{ $profil->email_resmi }}</span>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
            <div class="pt-8 border-t border-slate-800 text-center text-slate-500 text-sm">
                <p>&copy; {{ date('Y') }} {{ $namaSekolah }}. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
