<div>
    {{-- ============================================ --}}
    {{-- HERO --}}
    {{-- ============================================ --}}
    <section class="relative py-28 overflow-hidden bg-slate-950 noise">
        <div class="absolute inset-0">
            <div class="absolute top-[-20%] left-[-10%] w-[400px] h-[400px] bg-cyan-600/20 rounded-full blur-[100px] animate-blob"></div>
            <div class="absolute bottom-[-10%] right-[-5%] w-[300px] h-[300px] bg-blue-600/15 rounded-full blur-[80px] animate-blob" style="animation-delay:2s"></div>
            <div class="absolute top-[30%] right-[15%] w-[200px] h-[200px] bg-indigo-500/10 rounded-full blur-[80px] animate-float-slow"></div>
        </div>
        <div class="absolute inset-0 opacity-[0.03]" style="background-image: linear-gradient(rgba(255,255,255,0.1) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.1) 1px, transparent 1px); background-size: 60px 60px;"></div>
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-[15%] right-[12%] w-20 h-20 border border-white/10 rounded-2xl rotate-12 animate-float"></div>
            <div class="absolute bottom-[25%] left-[8%] w-14 h-14 border border-cyan-400/15 rounded-full animate-float-reverse"></div>
            <div class="absolute top-[40%] left-[45%] w-3 h-3 bg-cyan-400/40 rounded-full animate-float" style="animation-delay:1s"></div>
        </div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-cyan-300 glass rounded-full uppercase tracking-wider mb-6 animate-fade-up">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                Dokumentasi
            </span>
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight text-white mb-6 leading-[0.95] animate-fade-up delay-100">
                Galeri <span class="text-gradient">Foto & Video</span>
            </h1>
            <p class="text-lg text-slate-400 max-w-2xl animate-fade-up delay-200">Dokumentasi kegiatan, prestasi, dan momen berharga di SMKN 1 Kolaka.</p>
        </div>
    </section>

    {{-- ============================================ --}}
    {{-- GALLERY CONTENT --}}
    {{-- ============================================ --}}
    <section class="py-28 bg-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-cyan-50 rounded-full blur-[120px] -translate-y-1/2 translate-x-1/2"></div>
        <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-blue-50 rounded-full blur-[120px] translate-y-1/2 -translate-x-1/2"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            @if(!$selectedAlbum)
                {{-- Albums Grid --}}
                @if($albums->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($albums as $album)
                    <div wire:click="openAlbum({{ $album->id }})" class="cursor-pointer group">
                        <div class="aspect-[4/3] rounded-[28px] overflow-hidden bg-slate-100 mb-5 card-hover border border-slate-100">
                            @php $cover = $album->items->where('tipe_file', 'foto')->first(); @endphp
                            @if($cover)
                                <img src="{{ Storage::url($cover->file_path) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="{{ $album->judul_album }}">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-slate-50 to-slate-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                </div>
                            @endif
                        </div>
                        <h3 class="text-lg font-black text-slate-900 group-hover:text-blue-600 transition-colors">{{ $album->judul_album }}</h3>
                        <div class="flex items-center gap-3 mt-2">
                            <span class="inline-flex items-center gap-1 text-xs font-semibold text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                {{ $album->items_count }} item
                            </span>
                            @if($album->tanggal_kegiatan)
                            <span class="text-xs text-slate-400">· {{ $album->tanggal_kegiatan->format('d M Y') }}</span>
                            @endif
                        </div>
                        @if($album->deskripsi_singkat)
                        <p class="text-slate-500 text-sm mt-2 line-clamp-2">{{ $album->deskripsi_singkat }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-20">
                    <div class="w-20 h-20 bg-slate-100 rounded-[24px] flex items-center justify-center mx-auto mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    </div>
                    <p class="text-slate-400 text-lg font-semibold">Belum ada album galeri.</p>
                </div>
                @endif
            @else
                {{-- Album Detail --}}
                <div class="mb-10">
                    <button wire:click="closeAlbum" class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 hover:text-blue-700 transition-colors mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                        Kembali ke Album
                    </button>
                    <span class="inline-block px-4 py-1.5 text-xs font-bold text-cyan-600 bg-cyan-50 rounded-full uppercase tracking-wider mb-4">Album Detail</span>
                    <h2 class="text-3xl lg:text-4xl font-black tracking-tight text-slate-900 mb-3">{{ $selectedAlbum->judul_album }}</h2>
                    @if($selectedAlbum->deskripsi_singkat)
                    <p class="text-slate-500 max-w-2xl">{{ $selectedAlbum->deskripsi_singkat }}</p>
                    @endif
                </div>

                @if($selectedAlbum->items->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($selectedAlbum->items as $item)
                    <div class="group relative">
                        @if($item->tipe_file === 'foto')
                            <div class="aspect-square rounded-[20px] overflow-hidden bg-slate-100 card-hover border border-slate-100">
                                <img src="{{ Storage::url($item->file_path) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" alt="{{ $item->caption }}">
                            </div>
                        @else
                            <div class="aspect-square rounded-[20px] overflow-hidden bg-slate-900 flex items-center justify-center relative card-hover">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-white/80" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <a href="{{ $item->file_path }}" target="_blank" rel="noopener noreferrer" class="absolute inset-0"></a>
                            </div>
                        @endif
                        @if($item->caption)
                        <p class="text-sm text-slate-500 mt-2 font-medium">{{ $item->caption }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-12">
                    <p class="text-slate-400">Album ini belum memiliki item.</p>
                </div>
                @endif
            @endif
        </div>
    </section>
</div>
