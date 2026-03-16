<div>
    {{-- ============================================ --}}
    {{-- HERO --}}
    {{-- ============================================ --}}
    <section class="relative py-28 overflow-hidden bg-slate-950 noise">
        <div class="absolute inset-0">
            <div class="absolute top-[-20%] left-[-10%] w-[400px] h-[400px] bg-blue-600/20 rounded-full blur-[100px] animate-blob"></div>
            <div class="absolute bottom-[-10%] right-[-5%] w-[300px] h-[300px] bg-indigo-600/15 rounded-full blur-[80px] animate-blob" style="animation-delay:2s"></div>
            <div class="absolute top-[30%] right-[20%] w-[200px] h-[200px] bg-purple-500/10 rounded-full blur-[80px] animate-float-slow"></div>
        </div>
        <div class="absolute inset-0 opacity-[0.03]" style="background-image: linear-gradient(rgba(255,255,255,0.1) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.1) 1px, transparent 1px); background-size: 60px 60px;"></div>
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-[15%] right-[10%] w-20 h-20 border border-white/10 rounded-2xl rotate-12 animate-float"></div>
            <div class="absolute bottom-[25%] left-[5%] w-14 h-14 border border-blue-400/20 rounded-full animate-float-reverse"></div>
        </div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-blue-300 glass rounded-full uppercase tracking-wider mb-6 animate-fade-up">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" /></svg>
                Program Unggulan
            </span>
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight text-white mb-6 leading-[0.95] animate-fade-up delay-100">
                Program <span class="text-gradient">Keahlian</span>
            </h1>
            <p class="text-lg text-slate-400 max-w-2xl animate-fade-up delay-200">Temukan bidang keahlianmu dan mulai perjalanan menuju karir profesional bersama SMKN 1 Kolaka.</p>
        </div>
    </section>

    {{-- ============================================ --}}
    {{-- JURUSAN LIST --}}
    {{-- ============================================ --}}
    <section class="py-28 bg-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-blue-50 rounded-full blur-[120px] -translate-y-1/2 translate-x-1/2"></div>
        <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-indigo-50 rounded-full blur-[120px] translate-y-1/2 -translate-x-1/2"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            @if($jurusans->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @foreach($jurusans as $j)
                <a href="{{ route('jurusan.show', $j->slug) }}" class="group block bg-white rounded-[28px] border border-slate-100 overflow-hidden card-hover" wire:key="jurusan-{{ $j->id }}">
                    <div class="flex flex-col md:flex-row">
                        <div class="md:w-2/5 aspect-video md:aspect-auto overflow-hidden bg-slate-100 relative">
                            @if($j->gambar_cover)
                                <img src="{{ Storage::url($j->gambar_cover) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="{{ $j->nama_jurusan }}">
                            @else
                                <div class="w-full h-full min-h-[200px] flex items-center justify-center bg-gradient-to-br from-blue-500 via-indigo-500 to-purple-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-white/20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 14l9-5-9-5-9 5 9 5z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" /></svg>
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-r from-transparent to-black/10"></div>
                        </div>
                        <div class="md:w-3/5 p-8 flex flex-col justify-center">
                            <span class="inline-block px-3 py-1 text-[10px] font-bold text-blue-600 bg-blue-50 rounded-lg mb-3 w-fit uppercase tracking-wider">{{ $j->kode_jurusan }}</span>
                            <h3 class="text-xl font-black text-slate-900 mb-3 group-hover:text-blue-600 transition-colors">{{ $j->nama_jurusan }}</h3>
                            <p class="text-slate-500 text-sm line-clamp-3 mb-4">{{ $j->deskripsi_lengkap ? Str::limit(strip_tags($j->deskripsi_lengkap), 150) : 'Mempelajari fundamental dan praktik mendalam di bidang ' . strtolower($j->nama_jurusan) . '.' }}</p>
                            <span class="inline-flex items-center text-sm font-bold text-blue-600 gap-2 group-hover:gap-3 transition-all">
                                Selengkapnya
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                            </span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
            @else
            <div class="text-center py-20">
                <div class="w-20 h-20 bg-slate-100 rounded-[24px] flex items-center justify-center mx-auto mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 14l9-5-9-5-9 5 9 5z" /></svg>
                </div>
                <p class="text-slate-400 text-lg font-semibold">Belum ada program keahlian yang ditampilkan.</p>
            </div>
            @endif
        </div>
    </section>
</div>
