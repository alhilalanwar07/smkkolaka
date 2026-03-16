@php
    $shareText = rawurlencode($berita->judul);
    $shareUrl = rawurlencode($canonicalUrl);
    $shareLinks = [
        'WhatsApp' => 'https://wa.me/?text=' . $shareText . '%20' . $shareUrl,
        'Facebook' => 'https://www.facebook.com/sharer/sharer.php?u=' . $shareUrl,
        'X / Twitter' => 'https://twitter.com/intent/tweet?text=' . $shareText . '&url=' . $shareUrl,
        'LinkedIn' => 'https://www.linkedin.com/sharing/share-offsite/?url=' . $shareUrl,
    ];
@endphp

<div>
    {{-- ============================================ --}}
    {{-- HERO --}}
    {{-- ============================================ --}}
    <section class="relative py-28 overflow-hidden bg-slate-950 noise">
        <div class="absolute inset-0">
            <div class="absolute top-[-20%] left-[-10%] w-[400px] h-[400px] bg-purple-600/20 rounded-full blur-[100px] animate-blob"></div>
            <div class="absolute bottom-[-10%] right-[-5%] w-[300px] h-[300px] bg-blue-600/15 rounded-full blur-[80px] animate-blob" style="animation-delay:2s"></div>
        </div>
        <div class="absolute inset-0 opacity-[0.03]" style="background-image: linear-gradient(rgba(255,255,255,0.1) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.1) 1px, transparent 1px); background-size: 60px 60px;"></div>
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-[25%] right-[10%] w-16 h-16 border border-white/10 rounded-2xl rotate-12 animate-float"></div>
            <div class="absolute bottom-[20%] left-[8%] w-3 h-3 bg-purple-400/40 rounded-full animate-float-reverse" style="animation-delay:1s"></div>
        </div>
        <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6 flex flex-wrap items-center gap-2 text-sm text-slate-400 animate-fade-up">
                <a href="{{ route('berita.index') }}" class="transition hover:text-white">Berita</a>
                @if($berita->kategori)
                <span>/</span>
                <span>{{ $berita->kategori->nama_kategori }}</span>
                @endif
            </div>
            <a href="{{ route('berita.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-400 hover:text-white transition-colors mb-6 animate-fade-up">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                Kembali ke Berita
            </a>
            <div class="flex items-center gap-3 mb-5 animate-fade-up delay-100">
                @if($berita->kategori)
                <span class="px-3 py-1.5 text-[11px] font-bold text-white glass rounded-lg uppercase tracking-wider">{{ $berita->kategori->nama_kategori }}</span>
                @endif
                <span class="text-sm text-slate-400">{{ $berita->published_at?->format('d M Y') }}</span>
                <span class="text-sm text-slate-600">·</span>
                <span class="text-sm text-slate-400">{{ number_format($berita->view_count) }}x dilihat</span>
                <span class="text-sm text-slate-600">·</span>
                <span class="text-sm text-slate-400">{{ $readingMinutes }} menit baca</span>
            </div>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight text-white leading-tight animate-fade-up delay-200">{{ $berita->judul }}</h1>
            <p class="mt-6 max-w-3xl text-base leading-8 text-slate-300 animate-fade-up delay-300">{{ $metaDescription }}</p>
            <div class="mt-8 flex flex-wrap items-center gap-4 text-sm text-slate-300 animate-fade-up delay-300">
                <span>Oleh {{ $berita->user?->name ?? 'Administrator' }}</span>
                <span class="text-slate-600">•</span>
                <span>Diperbarui {{ $berita->updated_at?->format('d M Y H:i') }}</span>
            </div>
        </div>
    </section>

    {{-- ============================================ --}}
    {{-- ARTICLE CONTENT --}}
    {{-- ============================================ --}}
    <section class="py-28 bg-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-purple-50 rounded-full blur-[120px] -translate-y-1/2 translate-x-1/2"></div>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="mb-8 grid gap-4 rounded-[28px] border border-slate-200 bg-slate-50 p-6 text-sm text-slate-600 md:grid-cols-3">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-[0.24em] text-slate-400">Dipublikasikan</p>
                    <p class="mt-2 text-sm font-semibold text-slate-900">{{ $berita->published_at?->translatedFormat('d F Y, H:i') }}</p>
                </div>
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-[0.24em] text-slate-400">Diperbarui</p>
                    <p class="mt-2 text-sm font-semibold text-slate-900">{{ $berita->updated_at?->translatedFormat('d F Y, H:i') }}</p>
                </div>
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-[0.24em] text-slate-400">Bagikan</p>
                    <div class="mt-3 flex flex-wrap gap-2">
                        @foreach($shareLinks as $label => $link)
                        <a href="{{ $link }}" target="_blank" rel="noopener noreferrer" class="rounded-full border border-slate-200 bg-white px-3 py-2 text-xs font-bold text-slate-700 transition hover:border-blue-300 hover:text-blue-600">
                            {{ $label }}
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>

            @if($berita->gambar_thumbnail)
            <div class="rounded-[28px] overflow-hidden mb-12 shadow-xl">
                <img src="{{ Storage::url($berita->gambar_thumbnail) }}" class="w-full object-cover max-h-[500px]" alt="{{ $berita->judul }}">
            </div>
            @endif

            <article class="prose prose-lg prose-slate max-w-none prose-headings:font-black prose-a:text-blue-600 prose-img:rounded-2xl">
                {!! $berita->konten_html !!}
            </article>

            <div x-data="{ copied: false }" class="mt-10 rounded-[28px] border border-blue-100 bg-blue-50 p-6">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p class="text-base font-black text-slate-900">Bagikan artikel ini</p>
                        <p class="mt-1 text-sm text-slate-600">Tautan artikel sudah dioptimalkan untuk dibagikan ke media sosial, grup wali murid, dan kanal informasi sekolah.</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ $canonicalUrl }}" class="rounded-full bg-white px-4 py-2 text-xs font-bold text-slate-700 shadow-sm">Link Artikel</a>
                        <button type="button" @click="navigator.clipboard.writeText('{{ $canonicalUrl }}'); copied = true; setTimeout(() => copied = false, 2000)" class="rounded-full bg-blue-600 px-4 py-2 text-xs font-bold text-white transition hover:bg-blue-700">Salin Link</button>
                        <span x-show="copied" x-transition class="text-xs font-bold text-blue-600">Link tersalin</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================ --}}
    {{-- RELATED ARTICLES --}}
    {{-- ============================================ --}}
    @if($related->count() > 0)
    <section class="py-28 bg-slate-50 relative overflow-hidden">
        <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-indigo-50 rounded-full blur-[120px] translate-y-1/2 -translate-x-1/2"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="mb-14">
                <span class="inline-block px-4 py-1.5 text-xs font-bold text-purple-600 bg-purple-50 rounded-full uppercase tracking-wider mb-4">Baca Juga</span>
                <h2 class="text-3xl lg:text-4xl font-black tracking-tight text-slate-900">Berita <span class="text-gradient">Terkait</span></h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($related as $r)
                <article class="group bg-white rounded-[28px] overflow-hidden card-hover border border-slate-100">
                    <div class="aspect-video overflow-hidden bg-slate-100">
                        @if($r->gambar_thumbnail)
                            <img src="{{ Storage::url($r->gambar_thumbnail) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="{{ $r->judul }}">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-slate-50 to-slate-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" /></svg>
                            </div>
                        @endif
                    </div>
                    <div class="p-6">
                        <span class="text-xs text-slate-400">{{ $r->published_at?->format('d M Y') }}</span>
                        <h3 class="text-base font-bold text-slate-900 mt-2 group-hover:text-blue-600 transition-colors line-clamp-2">
                            <a href="{{ route('berita.show', $r->slug) }}">{{ $r->judul }}</a>
                        </h3>
                    </div>
                </article>
                @endforeach
            </div>
        </div>
    </section>
    @endif
</div>
