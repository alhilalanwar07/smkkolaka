<div class="space-y-8">
    @php
        $statMeta = [
            'Pegawai' => [
                'accent' => 'from-sky-500 to-blue-600',
                'soft' => 'bg-sky-50 text-sky-700 dark:bg-sky-950/40 dark:text-sky-300',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5V18a4 4 0 00-5.356-3.77"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 20H4V18a4 4 0 015.356-3.77"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 6a4 4 0 11-8 0 4 4 0 018 0z"/>' ,
                'caption' => 'Tenaga pendidik dan kependidikan aktif.',
            ],
            'Program Keahlian' => [
                'accent' => 'from-indigo-500 to-violet-600',
                'soft' => 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/40 dark:text-indigo-300',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>',
                'caption' => 'Pilihan kompetensi yang ditawarkan sekolah.',
            ],
            'Produk TEFA' => [
                'accent' => 'from-emerald-500 to-teal-600',
                'soft' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 7h16"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 7V5a2 2 0 012-2h6a2 2 0 012 2v2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 7l1 12a2 2 0 002 2h8a2 2 0 002-2l1-12"/>',
                'caption' => 'Unit karya dan layanan teaching factory.',
            ],
            'Berita' => [
                'accent' => 'from-amber-500 to-orange-600',
                'soft' => 'bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-300',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 8h6v4H7z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 16h10"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 9h4v9a2 2 0 01-2 2"/>',
                'caption' => 'Konten informasi yang sudah tersimpan.',
            ],
            'Pengumuman' => [
                'accent' => 'from-rose-500 to-pink-600',
                'soft' => 'bg-rose-50 text-rose-700 dark:bg-rose-950/40 dark:text-rose-300',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 5.882V19.24a1.75 1.75 0 01-3.398.563L5.5 14H4a2 2 0 01-2-2v-1a2 2 0 012-2h1.5l2.102-5.803A1.75 1.75 0 0111 3.76v2.122z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15.536 8.464a5 5 0 010 7.072"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M18.364 5.636a9 9 0 010 12.728"/>',
                'caption' => 'Informasi resmi yang siap dipublikasikan.',
            ],
            'Agenda' => [
                'accent' => 'from-cyan-500 to-sky-600',
                'soft' => 'bg-cyan-50 text-cyan-700 dark:bg-cyan-950/40 dark:text-cyan-300',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 3v3"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 3v3"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 8h16"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 5h14a1 1 0 011 1v13a2 2 0 01-2 2H6a2 2 0 01-2-2V6a1 1 0 011-1z"/>',
                'caption' => 'Kegiatan sekolah yang sudah dijadwalkan.',
            ],
            'Album Galeri' => [
                'accent' => 'from-fuchsia-500 to-violet-600',
                'soft' => 'bg-fuchsia-50 text-fuchsia-700 dark:bg-fuchsia-950/40 dark:text-fuchsia-300',
                'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16l4.5-4.5a2 2 0 012.828 0L16 16"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M14 14l1.5-1.5a2 2 0 012.828 0L20 14"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 9h.01"/>',
                'caption' => 'Dokumentasi visual kegiatan dan karya sekolah.',
            ],
        ];

        $heroStats = [
            'totalKonten' => collect($stats)->sum('value'),
            'beritaAktif' => $recentBerita->where('status_publikasi', 'published')->count(),
            'agendaMendatang' => $upcomingAgenda->count(),
        ];
    @endphp

    <section class="relative overflow-hidden rounded-[2rem] border border-slate-200 bg-gradient-to-br from-slate-950 via-slate-900 to-sky-950 px-6 py-7 text-white shadow-xl shadow-slate-900/10 dark:border-slate-800 md:px-8 md:py-8">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(56,189,248,0.2),_transparent_35%),radial-gradient(circle_at_bottom_left,_rgba(244,114,182,0.16),_transparent_30%)]"></div>
        <div class="relative grid gap-6 xl:grid-cols-[1.4fr_0.9fr] xl:items-end">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-[0.35em] text-sky-200/80">Dashboard Kendali</p>
                <h1 class="mt-3 max-w-2xl text-3xl font-black leading-tight text-white md:text-4xl">Pusat pantau konten, agenda, dan aktivitas utama sekolah.</h1>
                <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-300">
                    Ringkasan ini membantu superadmin membaca kondisi publikasi, ritme agenda, dan sebaran aset informasi sekolah dari satu layar yang lebih rapi dan cepat dipindai.
                </p>

                <div class="mt-6 flex flex-wrap gap-3 text-xs font-semibold text-slate-200">
                    <div class="rounded-full border border-white/15 bg-white/10 px-4 py-2 backdrop-blur">
                        Update {{ now()->translatedFormat('d F Y') }}
                    </div>
                    <div class="rounded-full border border-white/15 bg-white/10 px-4 py-2 backdrop-blur">
                        {{ $heroStats['agendaMendatang'] }} agenda menunggu pelaksanaan
                    </div>
                    <div class="rounded-full border border-white/15 bg-white/10 px-4 py-2 backdrop-blur">
                        {{ $heroStats['beritaAktif'] }} berita terbaru berstatus publish
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-3 sm:grid-cols-3 xl:grid-cols-1">
                <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur-sm">
                    <p class="text-xs font-bold uppercase tracking-[0.25em] text-slate-300">Total Entitas</p>
                    <p class="mt-2 text-3xl font-black text-white">{{ $heroStats['totalKonten'] }}</p>
                    <p class="mt-1 text-xs text-slate-300">Akumulasi item yang dikelola dari modul utama.</p>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur-sm sm:col-span-2 xl:col-span-1">
                    <p class="text-xs font-bold uppercase tracking-[0.25em] text-slate-300">Fokus Hari Ini</p>
                    <p class="mt-2 text-sm font-semibold text-white">Pantau berita baru, cek agenda terdekat, dan pastikan update publikasi berjalan stabil.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="grid grid-cols-1 gap-4 md:grid-cols-2 2xl:grid-cols-4">
        @foreach($stats as $stat)
            @php($meta = $statMeta[$stat['label']] ?? ['accent' => 'from-slate-500 to-slate-700', 'soft' => 'bg-slate-50 text-slate-700 dark:bg-slate-800 dark:text-slate-200', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6v12"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 12h12"/>', 'caption' => 'Ringkasan modul.'])
            <article class="group relative overflow-hidden rounded-[1.6rem] border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg dark:border-slate-800 dark:bg-slate-900">
                <div class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r {{ $meta['accent'] }}"></div>
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.25em] text-slate-400">{{ $stat['label'] }}</p>
                        <p class="mt-3 text-3xl font-black text-slate-900 dark:text-white">{{ $stat['value'] }}</p>
                    </div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl {{ $meta['soft'] }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">{!! $meta['icon'] !!}</svg>
                    </div>
                </div>
                <p class="mt-4 text-sm leading-6 text-slate-500 dark:text-slate-400">{{ $meta['caption'] }}</p>
            </article>
        @endforeach
    </section>

    <section class="grid grid-cols-1 gap-6 xl:grid-cols-[1.2fr_0.8fr]">
        <div class="overflow-hidden rounded-[1.8rem] border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="flex items-center justify-between gap-4 border-b border-slate-100 px-6 py-5 dark:border-slate-800">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.25em] text-amber-500">Publikasi</p>
                    <h2 class="mt-1 text-lg font-black text-slate-900 dark:text-white">Berita terbaru yang perlu dipantau</h2>
                </div>
                <a href="{{ route('admin.berita') }}" class="rounded-full bg-slate-100 px-4 py-2 text-xs font-bold text-slate-700 transition hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">Kelola berita</a>
            </div>

            <div class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($recentBerita as $berita)
                    <article class="flex flex-col gap-4 px-6 py-4 md:flex-row md:items-center md:justify-between">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="rounded-full px-2.5 py-1 text-[11px] font-bold uppercase tracking-[0.2em] {{ $berita->status_publikasi === 'published' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900 dark:text-emerald-300' : 'bg-amber-100 text-amber-700 dark:bg-amber-900 dark:text-amber-300' }}">
                                    {{ $berita->status_publikasi === 'published' ? 'Published' : 'Draft' }}
                                </span>
                                <span class="text-xs text-slate-400">{{ $berita->created_at->translatedFormat('d M Y H:i') }}</span>
                            </div>
                            <p class="mt-3 truncate text-sm font-bold text-slate-900 dark:text-white md:text-base">{{ $berita->judul }}</p>
                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                Ditulis {{ $berita->user?->name ?? 'Tim admin' }} • {{ $berita->created_at->diffForHumans() }}
                            </p>
                        </div>
                        <div class="shrink-0 text-right">
                            <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">Status Publikasi</p>
                            <p class="mt-1 text-sm font-semibold text-slate-700 dark:text-slate-200">
                                {{ $berita->status_publikasi === 'published' ? 'Sudah tayang' : 'Menunggu publikasi' }}
                            </p>
                        </div>
                    </article>
                @empty
                    <div class="px-6 py-12 text-center">
                        <p class="text-sm font-semibold text-slate-500 dark:text-slate-400">Belum ada berita yang tercatat.</p>
                        <p class="mt-1 text-xs text-slate-400">Mulai tambahkan berita sekolah agar dashboard publikasi lebih hidup.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="space-y-6">
            <div class="overflow-hidden rounded-[1.8rem] border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between gap-4 border-b border-slate-100 px-6 py-5 dark:border-slate-800">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.25em] text-sky-500">Agenda</p>
                        <h2 class="mt-1 text-lg font-black text-slate-900 dark:text-white">Kegiatan mendatang</h2>
                    </div>
                    <a href="{{ route('admin.agenda') }}" class="rounded-full bg-slate-100 px-4 py-2 text-xs font-bold text-slate-700 transition hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">Kelola agenda</a>
                </div>

                <div class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($upcomingAgenda as $agenda)
                        <article class="px-6 py-4">
                            <div class="flex items-start gap-4">
                                <div class="flex h-14 w-14 shrink-0 flex-col items-center justify-center rounded-2xl bg-sky-50 text-sky-700 dark:bg-sky-950/40 dark:text-sky-300">
                                    <span class="text-[10px] font-bold uppercase tracking-[0.2em]">{{ $agenda->waktu_mulai->translatedFormat('M') }}</span>
                                    <span class="text-lg font-black">{{ $agenda->waktu_mulai->translatedFormat('d') }}</span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-bold text-slate-900 dark:text-white md:text-base">{{ $agenda->nama_kegiatan }}</p>
                                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $agenda->waktu_mulai->translatedFormat('d M Y, H:i') }}</p>
                                    <p class="mt-2 text-xs text-slate-400">Lokasi: {{ $agenda->lokasi_pelaksanaan ?? 'Belum ditentukan' }}</p>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="px-6 py-12 text-center">
                            <p class="text-sm font-semibold text-slate-500 dark:text-slate-400">Belum ada agenda mendatang.</p>
                            <p class="mt-1 text-xs text-slate-400">Tambahkan agenda agar tim dapat memantau kegiatan berikutnya.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="rounded-[1.8rem] border border-slate-200 bg-gradient-to-br from-white via-slate-50 to-amber-50 p-6 shadow-sm dark:border-slate-800 dark:bg-gradient-to-br dark:from-slate-900 dark:via-slate-900 dark:to-slate-950">
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-slate-400">Sorotan Cepat</p>
                <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-3 xl:grid-cols-1">
                    <div>
                        <p class="text-2xl font-black text-slate-900 dark:text-white">{{ $heroStats['agendaMendatang'] }}</p>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">agenda aktif yang perlu disiapkan.</p>
                    </div>
                    <div>
                        <p class="text-2xl font-black text-slate-900 dark:text-white">{{ $heroStats['beritaAktif'] }}</p>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">berita terbaru berstatus publish.</p>
                    </div>
                    <div>
                        <p class="text-2xl font-black text-slate-900 dark:text-white">{{ collect($stats)->count() }}</p>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">modul utama sudah terhubung ke dashboard.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
