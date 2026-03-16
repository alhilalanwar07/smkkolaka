<div>
    {{-- ============================================ --}}
    {{-- HERO --}}
    {{-- ============================================ --}}
    <section class="relative py-28 overflow-hidden bg-slate-950 noise">
        <div class="absolute inset-0">
            <div class="absolute top-[-20%] left-[-10%] w-[400px] h-[400px] bg-indigo-600/20 rounded-full blur-[100px] animate-blob"></div>
            <div class="absolute bottom-[-10%] right-[-5%] w-[300px] h-[300px] bg-blue-600/15 rounded-full blur-[80px] animate-blob" style="animation-delay:2s"></div>
            <div class="absolute top-[35%] right-[20%] w-[200px] h-[200px] bg-purple-500/10 rounded-full blur-[80px] animate-float-slow"></div>
        </div>
        <div class="absolute inset-0 opacity-[0.03]" style="background-image: linear-gradient(rgba(255,255,255,0.1) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.1) 1px, transparent 1px); background-size: 60px 60px;"></div>
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-[20%] right-[8%] w-20 h-20 border border-white/10 rounded-2xl rotate-12 animate-float"></div>
            <div class="absolute bottom-[20%] left-[5%] w-14 h-14 border border-indigo-400/15 rounded-full animate-float-reverse"></div>
            <div class="absolute top-[50%] left-[40%] w-3 h-3 bg-indigo-400/40 rounded-full animate-float" style="animation-delay:2s"></div>
        </div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-indigo-300 glass rounded-full uppercase tracking-wider mb-6 animate-fade-up">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                Jadwal Kegiatan
            </span>
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight text-white mb-6 leading-[0.95] animate-fade-up delay-100">
                Agenda <span class="text-gradient">Kegiatan</span>
            </h1>
            <p class="text-lg text-slate-400 max-w-2xl animate-fade-up delay-200">Jadwal kegiatan, acara, dan event di SMKN 1 Kolaka.</p>
        </div>
    </section>

    {{-- ============================================ --}}
    {{-- FILTER --}}
    {{-- ============================================ --}}
    <section class="py-6 border-b border-slate-100 bg-white sticky top-[72px] z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex gap-2">
                @foreach(['upcoming' => 'Mendatang', 'past' => 'Selesai', 'all' => 'Semua'] as $key => $label)
                <button wire:click="$set('filter', '{{ $key }}')" class="px-5 py-2.5 text-xs font-bold rounded-full transition-all duration-200 uppercase tracking-wider {{ $filter === $key ? 'bg-slate-900 text-white shadow-lg shadow-slate-900/20' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">{{ $label }}</button>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================================ --}}
    {{-- AGENDA LIST --}}
    {{-- ============================================ --}}
    <section class="py-28 bg-slate-50 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-indigo-50 rounded-full blur-[120px] -translate-y-1/2 translate-x-1/2"></div>
        <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-blue-50 rounded-full blur-[120px] translate-y-1/2 -translate-x-1/2"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            @if($agendas->count() > 0)
            <div class="space-y-5">
                @foreach($agendas as $a)
                <div class="group bg-white rounded-[24px] border border-slate-100 p-7 card-hover flex flex-col sm:flex-row gap-6">
                    {{-- Date Card --}}
                    <div class="flex-shrink-0">
                        <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex flex-col items-center justify-center shadow-lg shadow-blue-500/20 group-hover:shadow-blue-500/40 transition-shadow">
                            <span class="text-2xl font-black text-white leading-none">{{ $a->waktu_mulai->format('d') }}</span>
                            <span class="text-[10px] font-bold text-blue-200 uppercase">{{ $a->waktu_mulai->format('M Y') }}</span>
                        </div>
                    </div>
                    {{-- Content --}}
                    <div class="flex-grow">
                        <h3 class="text-lg font-bold text-slate-900 mb-3 group-hover:text-blue-600 transition-colors">{{ $a->nama_kegiatan }}</h3>
                        <div class="flex flex-wrap gap-4 text-sm text-slate-500">
                            <span class="flex items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                {{ $a->waktu_mulai->format('H:i') }}
                                @if($a->waktu_selesai)
                                    - {{ $a->waktu_selesai->format('H:i') }}
                                @endif
                                WITA
                            </span>
                            @if($a->lokasi_pelaksanaan)
                            <span class="flex items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /></svg>
                                {{ $a->lokasi_pelaksanaan }}
                            </span>
                            @endif
                            @if($a->kategori_peserta)
                            <span class="flex items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                {{ ucfirst($a->kategori_peserta) }}
                            </span>
                            @endif
                        </div>
                        @if($a->deskripsi_kegiatan)
                        <p class="text-slate-500 text-sm mt-3 line-clamp-2">{{ Str::limit(strip_tags($a->deskripsi_kegiatan), 200) }}</p>
                        @endif
                    </div>
                    {{-- Status --}}
                    <div class="flex-shrink-0 self-center">
                        @if($a->waktu_mulai->isFuture())
                            <span class="px-4 py-1.5 text-xs font-bold rounded-full bg-emerald-100 text-emerald-700">Mendatang</span>
                        @else
                            <span class="px-4 py-1.5 text-xs font-bold rounded-full bg-slate-100 text-slate-500">Selesai</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-14">
                {{ $agendas->links() }}
            </div>
            @else
            <div class="text-center py-20">
                <div class="w-20 h-20 bg-slate-100 rounded-[24px] flex items-center justify-center mx-auto mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                </div>
                <p class="text-slate-400 text-lg font-semibold">Belum ada agenda.</p>
            </div>
            @endif
        </div>
    </section>
</div>
