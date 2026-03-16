<div>
    {{-- ============================================ --}}
    {{-- HERO --}}
    {{-- ============================================ --}}
    <section class="relative py-28 overflow-hidden bg-slate-950 noise">
        <div class="absolute inset-0">
            <div class="absolute top-[-20%] left-[-10%] w-[400px] h-[400px] bg-blue-600/20 rounded-full blur-[100px] animate-blob"></div>
            <div class="absolute bottom-[-10%] right-[-5%] w-[300px] h-[300px] bg-indigo-600/15 rounded-full blur-[80px] animate-blob" style="animation-delay:2s"></div>
        </div>
        <div class="absolute inset-0 opacity-[0.03]" style="background-image: linear-gradient(rgba(255,255,255,0.1) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.1) 1px, transparent 1px); background-size: 60px 60px;"></div>
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-[20%] right-[8%] w-16 h-16 border border-white/10 rounded-2xl rotate-12 animate-float"></div>
            <div class="absolute bottom-[30%] left-[10%] w-3 h-3 bg-blue-400/40 rounded-full animate-float-reverse" style="animation-delay:1s"></div>
        </div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <a href="{{ route('jurusan.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-400 hover:text-white transition-colors mb-6 animate-fade-up">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                Semua Program Keahlian
            </a>
            <span class="inline-block px-3 py-1.5 text-[11px] font-bold text-white glass rounded-lg mb-4 animate-fade-up delay-100">{{ $jurusan->kode_jurusan }}</span>
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight text-white leading-[0.95] animate-fade-up delay-200">{{ $jurusan->nama_jurusan }}</h1>
        </div>
    </section>

    {{-- ============================================ --}}
    {{-- CONTENT --}}
    {{-- ============================================ --}}
    <section class="py-28 bg-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-blue-50 rounded-full blur-[120px] -translate-y-1/2 translate-x-1/2"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                {{-- Main Content --}}
                <div class="lg:col-span-2 space-y-12">
                    @if($jurusan->gambar_cover)
                    <div class="rounded-[28px] overflow-hidden shadow-xl">
                        <img src="{{ Storage::url($jurusan->gambar_cover) }}" class="w-full object-cover max-h-[400px]" alt="{{ $jurusan->nama_jurusan }}">
                    </div>
                    @endif

                    @if($jurusan->deskripsi_lengkap)
                    <div>
                        <span class="inline-block px-4 py-1.5 text-xs font-bold text-blue-600 bg-blue-50 rounded-full uppercase tracking-wider mb-4">Tentang Program</span>
                        <h2 class="text-2xl lg:text-3xl font-black tracking-tight text-slate-900 mb-6">Deskripsi <span class="text-gradient">Program</span></h2>
                        <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed">
                            {!! nl2br(e($jurusan->deskripsi_lengkap)) !!}
                        </div>
                    </div>
                    @endif

                    @if($jurusan->fasilitas_unggulan)
                    <div>
                        <span class="inline-block px-4 py-1.5 text-xs font-bold text-indigo-600 bg-indigo-50 rounded-full uppercase tracking-wider mb-4">Fasilitas</span>
                        <h2 class="text-2xl lg:text-3xl font-black tracking-tight text-slate-900 mb-6">Fasilitas <span class="text-gradient">Unggulan</span></h2>
                        <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed">
                            {!! nl2br(e($jurusan->fasilitas_unggulan)) !!}
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Sidebar --}}
                <div class="space-y-8">
                    @if($jurusan->prospek_karir)
                    <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-[28px] p-8 text-white relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-[40px] -translate-y-1/2 translate-x-1/2"></div>
                        <div class="relative z-10">
                            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center mb-5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            </div>
                            <h3 class="text-lg font-black mb-4">Prospek Karir</h3>
                            <div class="text-blue-100 text-sm leading-relaxed">
                                {!! nl2br(e($jurusan->prospek_karir)) !!}
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="bg-slate-50 rounded-[28px] p-8 border border-slate-100">
                        <h3 class="text-lg font-black text-slate-900 mb-6">Info Singkat</h3>
                        <dl class="space-y-4 text-sm">
                            <div class="flex items-center justify-between py-3 border-b border-slate-100">
                                <dt class="text-slate-400 font-medium">Kode Jurusan</dt>
                                <dd class="text-slate-900 font-black">{{ $jurusan->kode_jurusan }}</dd>
                            </div>
                            <div class="flex items-center justify-between py-3">
                                <dt class="text-slate-400 font-medium">Status</dt>
                                <dd><span class="px-3 py-1 text-xs font-bold rounded-full bg-emerald-100 text-emerald-700">Aktif</span></dd>
                            </div>
                        </dl>
                    </div>

                    <a href="{{ route('jurusan.index') }}" class="block w-full text-center px-8 py-4 bg-slate-900 text-white font-bold rounded-2xl hover:bg-slate-800 transition-colors">
                        Lihat Program Lain
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
