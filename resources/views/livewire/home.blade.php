<div x-data="{
    shown: {},
    counters: {},
    startCounter(id, target) {
        if (this.counters[id] !== undefined) return;
        this.counters[id] = 0;
        const duration = 2000;
        const step = Math.max(1, Math.floor(target / (duration / 16)));
        const interval = setInterval(() => {
            this.counters[id] += step;
            if (this.counters[id] >= target) {
                this.counters[id] = target;
                clearInterval(interval);
            }
        }, 16);
    }
}" x-init="setTimeout(() => shown.hero = true, 100); @foreach($stats as $i => $s) startCounter('stat{{ $i }}', {{ $s['value'] }}); @endforeach">

    {{-- ============================================ --}}
    {{-- 1. HERO — Immersive Full-Screen --}}
    {{-- ============================================ --}}
    <section class="relative min-h-screen flex items-center overflow-hidden bg-slate-950 noise">
        {{-- Animated Gradient Mesh Background --}}
        <div class="absolute inset-0">
            <div class="absolute top-[-20%] left-[-10%] w-[600px] h-[600px] bg-blue-600/30 rounded-full blur-[120px] animate-blob"></div>
            <div class="absolute bottom-[-10%] right-[-5%] w-[500px] h-[500px] bg-indigo-600/25 rounded-full blur-[100px] animate-blob" style="animation-delay:2s"></div>
            <div class="absolute top-[30%] right-[20%] w-[400px] h-[400px] bg-purple-500/20 rounded-full blur-[100px] animate-blob" style="animation-delay:4s"></div>
            <div class="absolute bottom-[20%] left-[30%] w-[300px] h-[300px] bg-cyan-500/15 rounded-full blur-[80px] animate-float-slow"></div>
            <div class="absolute top-[10%] left-[50%] w-[200px] h-[200px] bg-emerald-500/10 rounded-full blur-[60px] animate-float" style="animation-delay:3s"></div>
        </div>

        {{-- Floating Geometric Shapes --}}
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-[15%] left-[8%] w-20 h-20 border border-white/10 rounded-2xl rotate-12 animate-float"></div>
            <div class="absolute top-[60%] right-[12%] w-16 h-16 border border-blue-400/20 rounded-full animate-float-reverse"></div>
            <div class="absolute top-[25%] right-[25%] w-3 h-3 bg-blue-400/40 rounded-full animate-float" style="animation-delay:1s"></div>
            <div class="absolute bottom-[30%] left-[15%] w-2 h-2 bg-purple-400/50 rounded-full animate-float-reverse" style="animation-delay:2s"></div>
            <div class="absolute top-[40%] left-[45%] w-4 h-4 bg-cyan-400/30 rounded-full animate-float-slow"></div>
            <div class="absolute top-[70%] left-[60%] w-24 h-24 border border-white/5 rounded-3xl -rotate-12 animate-float" style="animation-delay:3s"></div>
            <div class="absolute top-[8%] right-[8%] w-6 h-6 bg-amber-400/20 rounded-full animate-float" style="animation-delay:0.5s"></div>
            <div class="absolute bottom-[15%] right-[30%] w-10 h-10 border border-emerald-400/10 rounded-xl rotate-45 animate-float-reverse" style="animation-delay:1.5s"></div>
            {{-- Orbit rings --}}
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[300px] h-[300px] border border-white/[0.03] rounded-full hidden lg:block"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] border border-white/[0.02] rounded-full hidden lg:block"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[700px] border border-white/[0.01] rounded-full hidden lg:block"></div>
        </div>

        {{-- Grid Overlay --}}
        <div class="absolute inset-0 opacity-[0.03]" style="background-image: linear-gradient(rgba(255,255,255,0.1) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.1) 1px, transparent 1px); background-size: 60px 60px;"></div>

        {{-- Hero Content --}}
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32">
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-12 items-center">
                {{-- Left: Text Content --}}
                <div class="lg:col-span-3" x-show="shown.hero" x-transition:enter="transition ease-out duration-1000" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0">
                    {{-- Badge --}}
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full glass mb-8 animate-fade-up">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-400"></span>
                        </span>
                        <span class="text-xs font-bold text-emerald-300 tracking-wide uppercase">PPDB {{ date('Y') }} Telah Dibuka</span>
                    </div>

                    {{-- School Name Badge --}}
                    <div class="flex items-center gap-3 mb-6 animate-fade-up delay-100">
                        @if($profil && $profil->logo_path)
                        <img src="{{ Storage::url($profil->logo_path) }}" class="w-12 h-12 rounded-xl" alt="Logo">
                        @endif
                        <div>
                            <span class="text-sm font-bold text-white/80 uppercase tracking-wider">SMK Negeri 1 Kolaka</span>
                            <span class="block text-[10px] font-semibold text-blue-400 uppercase tracking-widest">Center of Excellence</span>
                        </div>
                    </div>

                    {{-- Main Heading --}}
                    <h1 class="text-5xl sm:text-6xl lg:text-7xl xl:text-8xl font-black tracking-tight text-white mb-8 leading-[0.95] animate-fade-up delay-200">
                        Masa Depan
                        <span class="block text-gradient">Dimulai</span>
                        <span class="block">di Sini.</span>
                    </h1>

                    <p class="text-lg sm:text-xl text-slate-400 mb-12 max-w-xl leading-relaxed animate-fade-up delay-300">
                        Tempat talenta muda bertransformasi menjadi profesional yang siap mengubah dunia melalui pendidikan vokasi berkualitas.
                    </p>

                    {{-- CTA Buttons --}}
                    <div class="flex flex-wrap gap-4 animate-fade-up delay-500">
                        <a href="{{ route('jurusan.index') }}" class="group relative px-8 py-4 bg-white text-slate-900 font-bold rounded-2xl overflow-hidden transition-all duration-300 hover:shadow-2xl hover:shadow-white/20 hover:-translate-y-1">
                            <span class="relative z-10 flex items-center gap-2">
                                Jelajahi Program
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                            </span>
                        </a>
                        <a href="{{ route('profil') }}" class="px-8 py-4 glass text-white font-bold rounded-2xl hover:bg-white/10 transition-all duration-300">
                            Tentang Kami
                        </a>
                    </div>

                    {{-- Trust Badges --}}
                    <div class="flex items-center gap-6 mt-10 animate-fade-up delay-700">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-emerald-500/20 rounded-lg flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                            </div>
                            <span class="text-xs text-slate-400 font-semibold">Akreditasi A</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-blue-500/20 rounded-lg flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                            </div>
                            <span class="text-xs text-slate-400 font-semibold">SMK PK</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-purple-500/20 rounded-lg flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>
                            </div>
                            <span class="text-xs text-slate-400 font-semibold">Sekolah Unggul</span>
                        </div>
                    </div>
                </div>

                {{-- Right: Stats Cards --}}
                <div class="hidden lg:grid lg:col-span-2 grid-cols-2 gap-4 animate-fade-up delay-700">
                    @foreach(array_slice($stats, 0, 4) as $index => $stat)
                    <div class="glass rounded-2xl px-5 py-6 text-center animate-float{{ $index % 2 === 0 ? '' : '-reverse' }}" style="animation-delay: {{ $index * 0.5 }}s">
                        <span class="block text-3xl font-black text-white mb-1">{{ $stat['value'] }}</span>
                        <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider leading-tight">{{ $stat['label'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Scroll Indicator --}}
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 animate-fade-up delay-1000">
            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Scroll</span>
            <div class="w-6 h-10 rounded-full border-2 border-slate-600 flex justify-center pt-2">
                <div class="w-1 h-2 bg-slate-400 rounded-full animate-bounce"></div>
            </div>
        </div>
    </section>

    {{-- ============================================ --}}
    {{-- 2. STATS COUNTER BAR — Animated Numbers --}}
    {{-- ============================================ --}}
    <section class="relative py-10 bg-white overflow-hidden border-b border-slate-100"
        x-data="{ visible: true }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
                @foreach($stats as $index => $stat)
                <div class="text-center group" x-show="visible" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-1 translate-y-0" style="transition-delay: {{ $index * 100 }}ms">
                    <div class="w-12 h-12 mx-auto bg-gradient-to-br {{ $stat['color'] }} rounded-2xl flex items-center justify-center mb-3 shadow-lg group-hover:scale-110 transition-transform duration-300">
                        @if($stat['icon'] === 'academic')
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" /></svg>
                        @elseif($stat['icon'] === 'users')
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        @elseif($stat['icon'] === 'newspaper')
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" /></svg>
                        @elseif($stat['icon'] === 'camera')
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        @elseif($stat['icon'] === 'cube')
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                        @elseif($stat['icon'] === 'calendar')
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        @endif
                    </div>
                    <span class="block text-3xl font-black text-slate-900" x-text="counters['stat{{ $index }}'] !== undefined ? counters['stat{{ $index }}'] : '0'">0</span>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-1">{{ $stat['label'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================================ --}}
    {{-- 3. MARQUEE PENGUMUMAN TICKER --}}
    {{-- ============================================ --}}
    @if($pengumuman->count() > 0)
    <section class="bg-gradient-to-r from-amber-500 via-orange-500 to-amber-500 py-3 overflow-hidden">
        <div class="flex animate-marquee whitespace-nowrap">
            @for($i = 0; $i < 3; $i++)
            @foreach($pengumuman as $p)
            <span class="mx-12 text-sm font-bold text-white flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" /></svg>
                {{ $p->judul_pengumuman }}
                <span class="text-white/60">—</span>
                <span class="text-white/80 font-normal">s/d {{ $p->tanggal_akhir_tampil->format('d M Y') }}</span>
            </span>
            @endforeach
            @endfor
        </div>
    </section>
    @endif

    {{-- ============================================ --}}
    {{-- 4. SAMBUTAN KEPALA SEKOLAH --}}
    {{-- ============================================ --}}
    @if($profil && $profil->teks_sambutan_kepsek)
    <section class="py-28 bg-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-blue-50 rounded-full blur-[120px] -translate-y-1/2 translate-x-1/2"></div>
        <div class="absolute bottom-0 left-0 w-[300px] h-[300px] bg-indigo-50 rounded-full blur-[80px] translate-y-1/2 -translate-x-1/2"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                {{-- Image --}}
                <div class="relative"
                    x-data="{ vis: true }">
                    <div class="relative z-10" x-show="vis" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 -translate-x-8" x-transition:enter-end="opacity-100 translate-x-0">
                        @if($profil->foto_kepsek)
                            <img src="{{ Storage::url($profil->foto_kepsek) }}" class="rounded-[32px] w-full object-cover max-h-[500px] shadow-2xl" alt="Kepala Sekolah">
                        @else
                            <div class="aspect-[4/5] rounded-[32px] bg-gradient-to-br from-blue-600 to-indigo-700 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-32 h-32 text-white/10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                            </div>
                        @endif
                    </div>
                    <div class="absolute -top-4 -left-4 w-full h-full rounded-[32px] border-2 border-blue-500/20 -z-0"></div>
                    <div class="absolute -bottom-4 -right-4 w-full h-full rounded-[32px] border-2 border-indigo-500/10 -z-0"></div>
                    {{-- Floating badge --}}
                    <div class="absolute -bottom-6 -right-6 lg:right-[-30px] glass-white rounded-2xl px-6 py-4 z-20 animate-float shadow-xl">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-xl flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            </div>
                            <div>
                                <span class="block text-2xl font-black text-slate-900">A</span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Akreditasi</span>
                            </div>
                        </div>
                    </div>
                    {{-- Top left year badge --}}
                    <div class="absolute -top-6 -left-6 lg:left-[-20px] glass-white rounded-2xl px-5 py-3 z-20 animate-float-reverse shadow-xl">
                        <span class="block text-lg font-black text-gradient-blue">Est. 1965</span>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Berdiri Sejak</span>
                    </div>
                </div>

                {{-- Content --}}
                <div x-data="{ vis: true }"
                    x-show="vis" x-transition:enter="transition ease-out duration-700 delay-200" x-transition:enter-start="opacity-0 translate-x-8" x-transition:enter-end="opacity-100 translate-x-0">
                    <span class="inline-block px-4 py-1.5 text-xs font-bold text-blue-600 bg-blue-50 rounded-full uppercase tracking-wider mb-6">Sambutan Kepala Sekolah</span>
                    <h2 class="text-3xl lg:text-4xl font-black tracking-tight text-slate-900 mb-8 leading-[1.1]">
                        Selamat Datang di<br><span class="text-gradient">SMKN 1 Kolaka</span>
                    </h2>

                    {{-- Quote mark --}}
                    <div class="relative">
                        <svg class="absolute -top-4 -left-4 w-12 h-12 text-blue-100" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10H14.017zM0 21v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151C7.563 6.068 6 8.789 6 11h4v10H0z"/></svg>
                        <div class="text-slate-600 leading-relaxed pl-8 border-l-4 border-blue-500/20 line-clamp-6">
                            {{ Str::limit($profil->teks_sambutan_kepsek, 400) }}
                        </div>
                    </div>

                    <a href="{{ route('profil') }}" class="inline-flex items-center gap-2 text-sm font-bold text-blue-600 hover:text-blue-700 mt-8 group">
                        Baca Selengkapnya
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                    </a>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- ============================================ --}}
    {{-- 5. JURUSAN — Cards --}}
    {{-- ============================================ --}}
    @if($jurusans->count() > 0)
    <section class="py-28 bg-slate-50 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-blue-50 rounded-full blur-[120px] -translate-y-1/2 translate-x-1/2"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between mb-16"
                x-data="{ vis: true }"
                x-show="vis" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 translate-y-6" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="max-w-xl">
                    <span class="inline-block px-4 py-1.5 text-xs font-bold text-blue-600 bg-blue-100 rounded-full uppercase tracking-wider mb-4">Program Unggulan</span>
                    <h2 class="text-4xl lg:text-5xl font-black tracking-tight text-slate-900 leading-[1.1]">
                        Konsentrasi<br>
                        <span class="text-gradient">Keahlian</span>
                    </h2>
                    <p class="text-slate-500 mt-4">Pilihan program keahlian terbaik yang dirancang untuk menghasilkan lulusan berkompeten tinggi.</p>
                </div>
                <a href="{{ route('jurusan.index') }}" class="mt-6 lg:mt-0 inline-flex items-center gap-2 px-6 py-3 bg-slate-900 text-white text-sm font-bold rounded-2xl hover:bg-slate-800 transition-colors group">
                    Lihat Semua
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-{{ min($jurusans->count(), 4) }} gap-6">
                @foreach($jurusans as $index => $j)
                <a href="{{ route('jurusan.show', $j->slug) }}" class="group relative bg-white rounded-[28px] overflow-hidden card-hover border border-slate-100" wire:key="jurusan-{{ $j->id }}"
                    x-data="{ vis: true }"
                    x-show="vis" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0" style="transition-delay: {{ $index * 100 }}ms">
                    <div class="aspect-[4/3] overflow-hidden relative">
                        @if($j->gambar_cover)
                            <img src="{{ Storage::url($j->gambar_cover) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="{{ $j->nama_jurusan }}">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-blue-500 via-indigo-500 to-purple-600 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-20 h-20 text-white/20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 14l9-5-9-5-9 5 9 5z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" /></svg>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent"></div>
                        <div class="absolute top-4 left-4">
                            <span class="px-3 py-1.5 text-[11px] font-bold text-white bg-white/20 backdrop-blur-md rounded-lg">{{ $j->kode_jurusan }}</span>
                        </div>
                        <div class="absolute bottom-4 left-4 right-4">
                            <h3 class="text-xl font-bold text-white leading-snug">{{ $j->nama_jurusan }}</h3>
                        </div>
                    </div>
                    <div class="p-6">
                        <p class="text-slate-500 text-sm line-clamp-2 mb-4">{{ $j->deskripsi_lengkap ? Str::limit(strip_tags($j->deskripsi_lengkap), 100) : 'Mempelajari fundamental dan praktik mendalam di bidang ' . strtolower($j->nama_jurusan) . '.' }}</p>
                        <span class="inline-flex items-center gap-2 text-sm font-bold text-blue-600 group-hover:text-blue-700">
                            Selengkapnya
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 group-hover:translate-x-2 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                        </span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ============================================ --}}
    {{-- 6. KEUNGGULAN — Bento Grid --}}
    {{-- ============================================ --}}
    <section class="py-28 bg-slate-950 text-white relative overflow-hidden noise">
        <div class="absolute top-0 left-0 w-[400px] h-[400px] bg-blue-600/10 rounded-full blur-[100px]"></div>
        <div class="absolute bottom-0 right-0 w-[400px] h-[400px] bg-indigo-600/10 rounded-full blur-[100px]"></div>
        <div class="absolute top-[50%] left-[50%] w-[300px] h-[300px] bg-purple-600/5 rounded-full blur-[80px]"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16"
                x-data="{ vis: true }"
                x-show="vis" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 translate-y-6" x-transition:enter-end="opacity-100 translate-y-0">
                <span class="inline-block px-4 py-1.5 text-xs font-bold text-blue-400 glass rounded-full uppercase tracking-wider mb-4">Kenapa Memilih Kami?</span>
                <h2 class="text-4xl lg:text-5xl font-black tracking-tight leading-[1.1]">
                    Bukan Sekadar<br>
                    <span class="text-gradient">Sekolah Biasa</span>
                </h2>
                <p class="text-slate-400 mt-4 max-w-2xl mx-auto">Kami memadukan kurikulum modern, fasilitas canggih, dan jaringan industri yang luas untuk mencetak lulusan terbaik.</p>
            </div>

            @php
            $features = [
                ['icon' => 'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z', 'title' => 'Kurikulum Industri 4.0', 'desc' => 'Materi disusun langsung bersama praktisi industri dengan pendekatan project-based learning.', 'color' => 'from-blue-400 to-blue-600'],
                ['icon' => 'M9.75 17L9 21h6l-.75-4M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'title' => 'Lab & Workshop Premium', 'desc' => 'Fasilitas standar industri untuk praktik langsung sejak semester pertama.', 'color' => 'from-indigo-400 to-indigo-600'],
                ['icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'title' => 'Langsung Kerja', 'desc' => 'Program magang dan kerjasama industri menjamin penyerapan lulusan tinggi.', 'color' => 'from-emerald-400 to-emerald-600'],
                ['icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'title' => 'Sertifikasi Kompetensi', 'desc' => 'Siswa mendapat sertifikasi profesi yang diakui dunia industri nasional & internasional.', 'color' => 'from-purple-400 to-purple-600'],
                ['icon' => 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Wawasan Global', 'desc' => 'Exposure internasional melalui program pertukaran dan kompetisi tingkat dunia.', 'color' => 'from-cyan-400 to-cyan-600'],
                ['icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'title' => 'Karakter Unggul', 'desc' => 'Pembinaan karakter, disiplin, dan kepemimpinan menjadi fondasi pendidikan kami.', 'color' => 'from-amber-400 to-amber-600'],
            ];
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($features as $index => $f)
                <div class="glass rounded-[28px] p-8 group hover:bg-white/10 transition-all duration-500"
                    x-data="{ vis: true }"
                    x-show="vis" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" style="transition-delay: {{ $index * 100 }}ms">
                    <div class="w-14 h-14 bg-gradient-to-br {{ $f['color'] }} rounded-2xl flex items-center justify-center mb-6 shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $f['icon'] }}" /></svg>
                    </div>
                    <h4 class="text-xl font-black text-white mb-3">{{ $f['title'] }}</h4>
                    <p class="text-slate-400 text-sm leading-relaxed">{{ $f['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================================ --}}
    {{-- 7. TEFA PRODUK SHOWCASE --}}
    {{-- ============================================ --}}
    @if($tefaProduks->count() > 0)
    <section class="py-28 bg-white relative overflow-hidden">
        <div class="absolute bottom-0 right-0 w-[400px] h-[400px] bg-emerald-50 rounded-full blur-[120px] translate-y-1/2 translate-x-1/2"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between mb-14"
                x-data="{ vis: true }"
                x-show="vis" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 translate-y-6" x-transition:enter-end="opacity-100 translate-y-0">
                <div>
                    <span class="inline-block px-4 py-1.5 text-xs font-bold text-emerald-600 bg-emerald-50 rounded-full uppercase tracking-wider mb-4">Teaching Factory</span>
                    <h2 class="text-4xl lg:text-5xl font-black tracking-tight text-slate-900">
                        Produk <span class="text-gradient">TEFA</span>
                    </h2>
                    <p class="text-slate-500 mt-4 max-w-lg">Karya nyata siswa melalui program Teaching Factory — belajar sambil memproduksi.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($tefaProduks as $index => $tp)
                <div class="group bg-white rounded-[24px] border border-slate-100 overflow-hidden card-hover"
                    x-data="{ vis: true }"
                    x-show="vis" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0" style="transition-delay: {{ $index * 100 }}ms">
                    <div class="aspect-square overflow-hidden bg-slate-100">
                        @if($tp->gambar_utama)
                            <img src="{{ Storage::url($tp->gambar_utama) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" alt="{{ $tp->nama_produk_jasa }}">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-emerald-50 to-emerald-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-emerald-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                            </div>
                        @endif
                    </div>
                    <div class="p-5">
                        <span class="inline-block px-2 py-0.5 text-[10px] font-bold text-emerald-600 bg-emerald-50 rounded uppercase tracking-wider mb-2">{{ $tp->status_ketersediaan }}</span>
                        <h4 class="font-bold text-slate-900 mb-1 line-clamp-1 group-hover:text-emerald-600 transition-colors">{{ $tp->nama_produk_jasa }}</h4>
                        @if($tp->harga_estimasi)
                        <p class="text-sm font-black text-gradient-blue">Rp {{ number_format($tp->harga_estimasi, 0, ',', '.') }}</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ============================================ --}}
    {{-- 8. GALERI PREVIEW — Masonry Grid --}}
    {{-- ============================================ --}}
    @if($galeriItems->count() > 0)
    <section class="py-28 bg-slate-950 text-white relative overflow-hidden noise">
        <div class="absolute inset-0">
            <div class="absolute top-[10%] right-[-5%] w-[400px] h-[400px] bg-cyan-600/10 rounded-full blur-[100px]"></div>
            <div class="absolute bottom-[10%] left-[5%] w-[300px] h-[300px] bg-blue-600/10 rounded-full blur-[80px]"></div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between mb-14"
                x-data="{ vis: true }"
                x-show="vis" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 translate-y-6" x-transition:enter-end="opacity-100 translate-y-0">
                <div>
                    <span class="inline-block px-4 py-1.5 text-xs font-bold text-cyan-400 glass rounded-full uppercase tracking-wider mb-4">Dokumentasi</span>
                    <h2 class="text-4xl lg:text-5xl font-black tracking-tight">
                        Galeri <span class="text-gradient">Kegiatan</span>
                    </h2>
                    <p class="text-slate-400 mt-4 max-w-lg">Momen-momen berharga yang terabadikan dari berbagai kegiatan di SMKN 1 Kolaka.</p>
                </div>
                <a href="{{ route('galeri') }}" class="mt-6 lg:mt-0 inline-flex items-center gap-2 px-6 py-3 glass text-white text-sm font-bold rounded-2xl hover:bg-white/10 transition-colors group">
                    Lihat Semua
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                </a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($galeriItems as $index => $item)
                <div class="group relative rounded-[20px] overflow-hidden {{ $index === 0 || $index === 5 ? 'md:col-span-2 md:row-span-2' : '' }}"
                    x-data="{ vis: true }"
                    x-show="vis" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" style="transition-delay: {{ $index * 80 }}ms">
                    <div class="aspect-square overflow-hidden bg-slate-800">
                        <img src="{{ Storage::url($item->file_path) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="{{ $item->caption ?? 'Galeri' }}">
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-5">
                        @if($item->caption)
                        <p class="text-sm text-white font-semibold">{{ $item->caption }}</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ============================================ --}}
    {{-- 9. AGENDA — Timeline Cards --}}
    {{-- ============================================ --}}
    @if($upcomingAgenda->count() > 0)
    <section class="py-28 bg-white relative overflow-hidden">
        <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-indigo-50 rounded-full blur-[120px] translate-y-1/2 -translate-x-1/2"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between mb-14"
                x-data="{ vis: true }"
                x-show="vis" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 translate-y-6" x-transition:enter-end="opacity-100 translate-y-0">
                <div>
                    <span class="inline-block px-4 py-1.5 text-xs font-bold text-indigo-600 bg-indigo-50 rounded-full uppercase tracking-wider mb-4">Jadwal Kegiatan</span>
                    <h2 class="text-4xl lg:text-5xl font-black tracking-tight text-slate-900">Agenda <span class="text-gradient">Mendatang</span></h2>
                </div>
                <a href="{{ route('agenda.index') }}" class="mt-6 lg:mt-0 inline-flex items-center gap-2 px-6 py-3 bg-slate-900 text-white text-sm font-bold rounded-2xl hover:bg-slate-800 transition-colors group">
                    Lihat Semua
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($upcomingAgenda as $index => $a)
                <div class="group relative bg-white rounded-[24px] border border-slate-100 p-7 card-hover"
                    x-data="{ vis: true }"
                    x-show="vis" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0" style="transition-delay: {{ $index * 100 }}ms">
                    <div class="flex items-center gap-4 mb-5">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex flex-col items-center justify-center shadow-lg shadow-blue-500/20 group-hover:shadow-blue-500/40 transition-shadow">
                            <span class="text-xl font-black text-white leading-none">{{ $a->waktu_mulai->format('d') }}</span>
                            <span class="text-[10px] font-bold text-blue-200 uppercase">{{ $a->waktu_mulai->format('M') }}</span>
                        </div>
                        <div>
                            <span class="text-sm font-bold text-slate-900 block">{{ $a->waktu_mulai->format('H:i') }} WITA</span>
                            <span class="text-xs text-slate-400">{{ $a->waktu_mulai->format('Y') }}</span>
                        </div>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-3 line-clamp-2 group-hover:text-blue-600 transition-colors">{{ $a->nama_kegiatan }}</h3>
                    @if($a->lokasi_pelaksanaan)
                    <p class="text-xs text-slate-400 flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /></svg>
                        {{ $a->lokasi_pelaksanaan }}
                    </p>
                    @endif
                    <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-blue-50 to-transparent rounded-bl-[40px] rounded-tr-[24px] -z-0"></div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ============================================ --}}
    {{-- 10. TENAGA PENDIDIK PREVIEW --}}
    {{-- ============================================ --}}
    @if($pegawaiHighlight->count() > 0)
    <section class="py-28 bg-slate-50 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-[400px] h-[400px] bg-purple-50 rounded-full blur-[120px] -translate-y-1/2 -translate-x-1/2"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-14"
                x-data="{ vis: true }"
                x-show="vis" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 translate-y-6" x-transition:enter-end="opacity-100 translate-y-0">
                <span class="inline-block px-4 py-1.5 text-xs font-bold text-purple-600 bg-purple-50 rounded-full uppercase tracking-wider mb-4">Tim Profesional</span>
                <h2 class="text-4xl lg:text-5xl font-black tracking-tight text-slate-900">
                    Tenaga <span class="text-gradient">Pendidik</span>
                </h2>
                <p class="text-slate-500 mt-4 max-w-xl mx-auto">Didukung oleh tenaga pendidik berpengalaman dan berdedikasi tinggi untuk masa depan siswa.</p>
            </div>

            {{-- Horizontal scrollable on mobile --}}
            <div class="flex gap-6 overflow-x-auto pb-4 snap-x snap-mandatory lg:grid lg:grid-cols-4 lg:overflow-visible lg:pb-0">
                @foreach($pegawaiHighlight as $index => $p)
                <div class="snap-center shrink-0 w-[200px] lg:w-auto group"
                    x-data="{ vis: true }"
                    x-show="vis" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" style="transition-delay: {{ $index * 80 }}ms">
                    <div class="bg-white rounded-[24px] border border-slate-100 overflow-hidden text-center card-hover">
                        <div class="aspect-square bg-slate-100 overflow-hidden">
                            @if($p->foto_profil)
                                <img src="{{ Storage::url($p->foto_profil) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" alt="{{ $p->nama_lengkap }}">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-slate-50 to-slate-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                </div>
                            @endif
                        </div>
                        <div class="p-4">
                            <h4 class="font-bold text-slate-900 text-sm line-clamp-1">{{ $p->nama_lengkap }}</h4>
                            <p class="text-xs text-slate-400 mt-1">{{ $p->jabatan }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="text-center mt-10"
                x-data="{ vis: true }"
                x-show="vis" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                <a href="{{ route('profil') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-slate-900 text-white text-sm font-bold rounded-2xl hover:bg-slate-800 transition-colors group">
                    Lihat Semua Tenaga Pendidik
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                </a>
            </div>
        </div>
    </section>
    @endif

    {{-- ============================================ --}}
    {{-- 11. BERITA — Magazine-Style Layout --}}
    {{-- ============================================ --}}
    @if($recentBerita->count() > 0)
    <section class="py-28 bg-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-purple-50 rounded-full blur-[120px] -translate-y-1/2 translate-x-1/2"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between mb-14"
                x-data="{ vis: true }"
                x-show="vis" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 translate-y-6" x-transition:enter-end="opacity-100 translate-y-0">
                <div>
                    <span class="inline-block px-4 py-1.5 text-xs font-bold text-purple-600 bg-purple-50 rounded-full uppercase tracking-wider mb-4">Berita Terbaru</span>
                    <h2 class="text-4xl lg:text-5xl font-black tracking-tight text-slate-900">
                        Berita & <span class="text-gradient">Artikel</span>
                    </h2>
                </div>
                <a href="{{ route('berita.index') }}" class="mt-6 lg:mt-0 inline-flex items-center gap-2 px-6 py-3 bg-slate-900 text-white text-sm font-bold rounded-2xl hover:bg-slate-800 transition-colors group">
                    Lihat Semua
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                </a>
            </div>

            @if($recentBerita->count() >= 3)
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <article class="group relative bg-white rounded-[28px] overflow-hidden card-hover border border-slate-100 row-span-2"
                    x-data="{ vis: true }"
                    x-show="vis" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 -translate-x-8" x-transition:enter-end="opacity-100 translate-x-0">
                    <div class="aspect-[4/3] overflow-hidden bg-slate-100">
                        @if($recentBerita[0]->gambar_thumbnail)
                            <img src="{{ Storage::url($recentBerita[0]->gambar_thumbnail) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="{{ $recentBerita[0]->judul }}">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-20 h-20 text-white/20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" /></svg>
                            </div>
                        @endif
                    </div>
                    <div class="p-8">
                        <div class="flex items-center gap-3 mb-4">
                            @if($recentBerita[0]->kategori)
                            <span class="px-3 py-1 bg-blue-50 text-blue-600 text-[10px] font-bold uppercase tracking-wider rounded-lg">{{ $recentBerita[0]->kategori->nama_kategori }}</span>
                            @endif
                            <span class="text-xs text-slate-400">{{ $recentBerita[0]->published_at?->format('d M Y') }}</span>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-3 group-hover:text-blue-600 transition-colors leading-snug">
                            <a href="{{ route('berita.show', $recentBerita[0]->slug) }}">{{ $recentBerita[0]->judul }}</a>
                        </h3>
                        <p class="text-slate-500 line-clamp-3">{{ Str::limit(strip_tags($recentBerita[0]->konten_html), 180) }}</p>
                    </div>
                </article>

                @foreach($recentBerita->skip(1) as $si => $berita)
                <article class="group bg-white rounded-[28px] overflow-hidden card-hover border border-slate-100 flex flex-col sm:flex-row"
                    x-data="{ vis: true }"
                    x-show="vis" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-x-8" x-transition:enter-end="opacity-100 translate-x-0" style="transition-delay: {{ $si * 150 }}ms">
                    <div class="sm:w-2/5 aspect-video sm:aspect-auto overflow-hidden bg-slate-100">
                        @if($berita->gambar_thumbnail)
                            <img src="{{ Storage::url($berita->gambar_thumbnail) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" alt="{{ $berita->judul }}">
                        @else
                            <div class="w-full h-full min-h-[160px] bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" /></svg>
                            </div>
                        @endif
                    </div>
                    <div class="sm:w-3/5 p-6 flex flex-col justify-center">
                        <div class="flex items-center gap-3 mb-2">
                            @if($berita->kategori)
                            <span class="px-2 py-0.5 bg-blue-50 text-blue-600 text-[10px] font-bold uppercase tracking-wider rounded">{{ $berita->kategori->nama_kategori }}</span>
                            @endif
                            <span class="text-xs text-slate-400">{{ $berita->published_at?->format('d M Y') }}</span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-blue-600 transition-colors leading-snug line-clamp-2">
                            <a href="{{ route('berita.show', $berita->slug) }}">{{ $berita->judul }}</a>
                        </h3>
                        <p class="text-slate-500 text-sm line-clamp-2 mt-2">{{ Str::limit(strip_tags($berita->konten_html), 100) }}</p>
                    </div>
                </article>
                @endforeach
            </div>
            @else
            <div class="grid grid-cols-1 md:grid-cols-{{ $recentBerita->count() }} gap-8">
                @foreach($recentBerita as $berita)
                <article class="bg-white rounded-[28px] overflow-hidden group card-hover border border-slate-100">
                    <div class="aspect-video overflow-hidden bg-slate-100">
                        @if($berita->gambar_thumbnail)
                            <img src="{{ Storage::url($berita->gambar_thumbnail) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="{{ $berita->judul }}">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-slate-100 to-slate-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" /></svg>
                            </div>
                        @endif
                    </div>
                    <div class="p-7">
                        <div class="flex items-center gap-3 mb-3">
                            @if($berita->kategori)
                            <span class="px-3 py-1 bg-blue-50 text-blue-600 text-[10px] font-bold uppercase tracking-wider rounded-lg">{{ $berita->kategori->nama_kategori }}</span>
                            @endif
                            <span class="text-xs text-slate-400">{{ $berita->published_at?->format('d M Y') }}</span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3 group-hover:text-blue-600 transition-colors leading-snug line-clamp-2">
                            <a href="{{ route('berita.show', $berita->slug) }}">{{ $berita->judul }}</a>
                        </h3>
                        <p class="text-slate-500 text-sm line-clamp-2">{{ Str::limit(strip_tags($berita->konten_html), 120) }}</p>
                    </div>
                </article>
                @endforeach
            </div>
            @endif
        </div>
    </section>
    @endif

    {{-- ============================================ --}}
    {{-- 12. VISI MISI QUICK --}}
    {{-- ============================================ --}}
    @if($profil && ($profil->visi_teks || $profil->misi_teks))
    <section class="py-28 bg-slate-50 relative overflow-hidden">
        <div class="absolute bottom-0 right-0 w-[400px] h-[400px] bg-blue-50 rounded-full blur-[120px] translate-y-1/2 translate-x-1/2"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-14"
                x-data="{ vis: true }"
                x-show="vis" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 translate-y-6" x-transition:enter-end="opacity-100 translate-y-0">
                <span class="inline-block px-4 py-1.5 text-xs font-bold text-indigo-600 bg-indigo-50 rounded-full uppercase tracking-wider mb-4">Arah & Tujuan</span>
                <h2 class="text-4xl lg:text-5xl font-black tracking-tight text-slate-900">Visi & <span class="text-gradient">Misi</span></h2>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                @if($profil->visi_teks)
                <div class="bg-white p-10 rounded-[28px] border border-slate-100 card-hover"
                    x-data="{ vis: true }"
                    x-show="vis" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 -translate-x-8" x-transition:enter-end="opacity-100 translate-x-0">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-blue-500/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-4">Visi</h3>
                    <div class="text-slate-600 leading-relaxed">{!! nl2br(e(Str::limit($profil->visi_teks, 300))) !!}</div>
                </div>
                @endif
                @if($profil->misi_teks)
                <div class="bg-white p-10 rounded-[28px] border border-slate-100 card-hover"
                    x-data="{ vis: true }"
                    x-show="vis" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-x-8" x-transition:enter-end="opacity-100 translate-x-0">
                    <div class="w-14 h-14 bg-gradient-to-br from-indigo-400 to-indigo-600 rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-indigo-500/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" /></svg>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-4">Misi</h3>
                    <div class="text-slate-600 leading-relaxed">{!! nl2br(e(Str::limit($profil->misi_teks, 300))) !!}</div>
                </div>
                @endif
            </div>
        </div>
    </section>
    @endif

    {{-- ============================================ --}}
    {{-- 13. LOKASI / PETA --}}
    {{-- ============================================ --}}
    @if($profil && $profil->alamat_lengkap)
    <section class="py-28 bg-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center"
                x-data="{ vis: true }">
                <div x-show="vis" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 -translate-x-8" x-transition:enter-end="opacity-100 translate-x-0">
                    <span class="inline-block px-4 py-1.5 text-xs font-bold text-emerald-600 bg-emerald-50 rounded-full uppercase tracking-wider mb-6">Lokasi Kami</span>
                    <h2 class="text-3xl lg:text-4xl font-black tracking-tight text-slate-900 mb-6">Temukan <span class="text-gradient">Kami</span></h2>
                    <p class="text-slate-600 leading-relaxed mb-8">{{ $profil->alamat_lengkap }}</p>

                    <div class="space-y-4">
                        @if($profil->nomor_telepon)
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Telepon</span>
                                <p class="font-bold text-slate-900">{{ $profil->nomor_telepon }}</p>
                            </div>
                        </div>
                        @endif
                        @if($profil->email_resmi)
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-indigo-400 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            </div>
                            <div>
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Email</span>
                                <p class="font-bold text-slate-900">{{ $profil->email_resmi }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Map Placeholder --}}
                <div class="rounded-[28px] overflow-hidden shadow-xl border border-slate-100 aspect-[4/3] bg-slate-100"
                    x-show="vis" x-transition:enter="transition ease-out duration-700 delay-200" x-transition:enter-start="opacity-0 translate-x-8" x-transition:enter-end="opacity-100 translate-x-0">
                    @if($profil->koordinat_peta)
                        @php
                            $coords = explode(',', $profil->koordinat_peta);
                            $lat = trim($coords[0] ?? '-4.0');
                            $lng = trim($coords[1] ?? '121.6');
                        @endphp
                        <iframe src="https://maps.google.com/maps?q={{ $lat }},{{ $lng }}&z=15&output=embed" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="w-full h-full"></iframe>
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-20 h-20 mb-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            <p class="font-semibold">Peta belum tersedia</p>
                            <p class="text-sm mt-1">Silakan atur koordinat di panel admin</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- ============================================ --}}
    {{-- 14. CTA — Full-Width Gradient --}}
    {{-- ============================================ --}}
    <section class="relative py-32 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-700 animate-gradient"></div>
        <div class="absolute inset-0 noise"></div>
        <div class="absolute top-10 left-10 w-32 h-32 border border-white/10 rounded-full animate-float"></div>
        <div class="absolute bottom-10 right-10 w-48 h-48 border border-white/10 rounded-3xl rotate-12 animate-float-reverse"></div>
        <div class="absolute top-1/2 left-1/3 -translate-y-1/2 w-24 h-24 border border-white/5 rounded-2xl rotate-45 animate-float-slow"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] border border-white/5 rounded-full"></div>
        <div class="absolute top-[20%] right-[20%] w-4 h-4 bg-white/10 rounded-full animate-float" style="animation-delay:1s"></div>
        <div class="absolute bottom-[25%] left-[25%] w-3 h-3 bg-white/15 rounded-full animate-float-reverse" style="animation-delay:2s"></div>

        <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center"
            x-data="{ vis: true }"
            x-show="vis" x-transition:enter="transition ease-out duration-700" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 backdrop-blur-md border border-white/20 mb-8">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-white"></span>
                </span>
                <span class="text-xs font-bold text-white tracking-wide uppercase">Pendaftaran Dibuka</span>
            </div>
            <h2 class="text-4xl lg:text-6xl font-black text-white mb-6 leading-tight">
                Siap Memulai<br>Perjalananmu?
            </h2>
            <p class="text-lg text-blue-100 mb-12 max-w-2xl mx-auto">
                Bergabunglah dengan ribuan alumni sukses SMKN 1 Kolaka. Masa depan cerahmu dimulai dari langkah pertama.
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('jurusan.index') }}" class="px-10 py-5 bg-white text-slate-900 font-bold rounded-2xl hover:shadow-2xl hover:shadow-white/20 hover:-translate-y-1 transition-all duration-300 text-lg">
                    Daftar Sekarang
                </a>
                <a href="{{ route('profil') }}" class="px-10 py-5 bg-white/10 backdrop-blur-md border border-white/20 text-white font-bold rounded-2xl hover:bg-white/20 transition-all duration-300 text-lg">
                    Pelajari Lebih Lanjut
                </a>
            </div>
        </div>
    </section>

</div>
