<div>
    {{-- ============================================ --}}
    {{-- HERO --}}
    {{-- ============================================ --}}
    <section class="relative py-28 overflow-hidden bg-slate-950 noise">
        <div class="absolute inset-0">
            <div class="absolute top-[-20%] left-[-10%] w-[400px] h-[400px] bg-purple-600/20 rounded-full blur-[100px] animate-blob"></div>
            <div class="absolute bottom-[-10%] right-[-5%] w-[300px] h-[300px] bg-blue-600/15 rounded-full blur-[80px] animate-blob" style="animation-delay:2s"></div>
            <div class="absolute top-[40%] right-[25%] w-[200px] h-[200px] bg-indigo-500/10 rounded-full blur-[80px] animate-float-slow"></div>
        </div>
        <div class="absolute inset-0 opacity-[0.03]" style="background-image: linear-gradient(rgba(255,255,255,0.1) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.1) 1px, transparent 1px); background-size: 60px 60px;"></div>
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-[20%] left-[8%] w-16 h-16 border border-white/10 rounded-2xl rotate-12 animate-float"></div>
            <div class="absolute bottom-[20%] right-[12%] w-20 h-20 border border-purple-400/10 rounded-full animate-float-reverse"></div>
        </div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-purple-300 glass rounded-full uppercase tracking-wider mb-6 animate-fade-up">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" /></svg>
                Berita & Artikel
            </span>
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight text-white mb-6 leading-[0.95] animate-fade-up delay-100">
                Berita & <span class="text-gradient">Artikel</span>
            </h1>
            <p class="text-lg text-slate-400 max-w-2xl animate-fade-up delay-200">Ikuti perkembangan terbaru, kegiatan, dan prestasi di SMKN 1 Kolaka.</p>
        </div>
    </section>

    {{-- ============================================ --}}
    {{-- FILTERS --}}
    {{-- ============================================ --}}
    <section class="py-6 border-b border-slate-100 bg-white sticky top-[72px] z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row gap-4 items-center justify-between">
                <div class="flex flex-wrap gap-2">
                    <button wire:click="$set('kategoriFilter', '')" class="px-5 py-2.5 text-xs font-bold rounded-full transition-all duration-200 uppercase tracking-wider {{ $kategoriFilter === '' ? 'bg-slate-900 text-white shadow-lg shadow-slate-900/20' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Semua</button>
                    @foreach($kategoris as $kat)
                        <button wire:click="$set('kategoriFilter', '{{ $kat->id }}')" class="px-5 py-2.5 text-xs font-bold rounded-full transition-all duration-200 uppercase tracking-wider {{ $kategoriFilter == $kat->id ? 'bg-slate-900 text-white shadow-lg shadow-slate-900/20' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">{{ $kat->nama_kategori }}</button>
                    @endforeach
                </div>
                <div class="relative w-full sm:w-72">
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari berita..." class="w-full pl-11 pr-4 py-3 rounded-2xl border border-slate-200 text-sm font-medium outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 bg-slate-50 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 absolute left-4 top-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================ --}}
    {{-- ARTICLES GRID --}}
    {{-- ============================================ --}}
    <section class="py-28 bg-slate-50 relative overflow-hidden">
        <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-purple-50 rounded-full blur-[120px] translate-y-1/2 -translate-x-1/2"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            @if($berita->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($berita as $b)
                <article class="group bg-white rounded-[28px] overflow-hidden card-hover border border-slate-100">
                    <div class="aspect-video overflow-hidden bg-slate-100">
                        @if($b->gambar_thumbnail)
                            <img src="{{ Storage::url($b->gambar_thumbnail) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="{{ $b->judul }}">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-slate-50 to-slate-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" /></svg>
                            </div>
                        @endif
                    </div>
                    <div class="p-7">
                        <div class="flex items-center gap-3 mb-3">
                            @if($b->kategori)
                            <span class="px-3 py-1 bg-blue-50 text-blue-600 text-[10px] font-bold uppercase tracking-wider rounded-lg">{{ $b->kategori->nama_kategori }}</span>
                            @endif
                            <span class="text-xs text-slate-400">{{ $b->published_at?->format('d M Y') }}</span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-3 group-hover:text-blue-600 transition-colors leading-snug line-clamp-2">
                            <a href="{{ route('berita.show', $b->slug) }}">{{ $b->judul }}</a>
                        </h3>
                        <p class="text-slate-500 text-sm line-clamp-2">{{ Str::limit(strip_tags($b->konten_html), 120) }}</p>
                    </div>
                </article>
                @endforeach
            </div>

            <div class="mt-14">
                {{ $berita->links() }}
            </div>
            @else
            <div class="text-center py-20">
                <div class="w-20 h-20 bg-slate-100 rounded-[24px] flex items-center justify-center mx-auto mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" /></svg>
                </div>
                <p class="text-slate-400 text-lg font-semibold">Belum ada berita yang diterbitkan.</p>
            </div>
            @endif
        </div>
    </section>
</div>
