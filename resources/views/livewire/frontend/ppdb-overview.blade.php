<div>
    @php($periodQuery = $selectedPeriodId ? ['periode' => $selectedPeriodId] : [])

    <section class="relative overflow-hidden bg-slate-950 text-white noise">
        <div class="absolute inset-0 bg-mesh-hero opacity-70"></div>
        <div class="absolute inset-0">
            <div class="absolute top-[-10%] left-[-5%] w-[28rem] h-[28rem] rounded-full bg-blue-600/20 blur-[110px] animate-blob"></div>
            <div class="absolute bottom-[-15%] right-[-10%] w-[30rem] h-[30rem] rounded-full bg-indigo-600/20 blur-[120px] animate-blob" style="animation-delay: 2s"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-10 items-center">
                <div class="lg:col-span-3">
                    <span class="inline-flex px-4 py-1.5 rounded-full glass text-xs font-bold uppercase tracking-[0.3em] text-blue-300 mb-6">PPDB SMKN 1 Kolaka</span>
                    <h1 class="text-4xl md:text-6xl xl:text-7xl font-black tracking-tight leading-[0.95]">
                        Pusat Informasi
                        <span class="block text-gradient">PPDB Online</span>
                    </h1>
                    <p class="mt-6 text-lg text-slate-300 max-w-2xl leading-relaxed">Halaman ini menjadi pusat informasi resmi PPDB. Calon siswa bisa mempelajari jalur, kuota, jadwal, alur seleksi, hasil resmi, dan jalur menuju formulir maupun daftar ulang dari satu tempat.</p>
                    <div class="mt-8 max-w-xl rounded-[24px] border border-white/10 bg-white/5 p-4 backdrop-blur">
                        <label class="text-xs font-bold uppercase tracking-[0.25em] text-slate-300">Pilih Tahun Ajaran / Gelombang</label>
                        <select wire:model.live="selectedPeriod" class="mt-3 w-full rounded-2xl border border-white/10 bg-slate-950/60 px-4 py-3 text-sm text-white outline-none">
                            @foreach($availablePeriods as $periodOption)
                                <option value="{{ $periodOption->id }}">{{ $periodOption->full_label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex flex-wrap gap-4 mt-10">
                        @if($period)
                        <a href="{{ route('ppdb.form', $periodQuery) }}" class="px-7 py-4 rounded-2xl bg-white text-slate-900 font-bold hover:-translate-y-1 transition">Buka Form Pendaftaran</a>
                        <a href="{{ route('ppdb.status') }}" class="px-7 py-4 rounded-2xl glass font-bold hover:bg-white/10 transition">Cek Status</a>
                        @if($period->isAnnouncementPublished())
                        <a href="{{ route('ppdb.daftar-ulang') }}" class="px-7 py-4 rounded-2xl bg-emerald-500/90 text-white font-bold hover:bg-emerald-500 transition">Daftar Ulang</a>
                        @endif
                        @else
                        <a href="{{ route('ppdb.status') }}" class="px-7 py-4 rounded-2xl glass font-bold hover:bg-white/10 transition">Cek Status</a>
                        @endif
                    </div>
                </div>
                <div class="lg:col-span-2 space-y-4">
                    <div class="glass rounded-[28px] p-6">
                        <p class="text-xs uppercase tracking-[0.25em] text-slate-400 font-bold">Periode Dipilih</p>
                        <h2 class="text-2xl font-black mt-3">{{ $period?->nama_periode ?? 'Belum Dibuka' }}</h2>
                        <p class="text-sm text-slate-300 mt-1">{{ $period?->tahun_ajaran ?? 'Menunggu publikasi panitia' }} @if($period?->gelombang_label) · {{ $period->gelombang_label }} @endif</p>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="glass rounded-[24px] p-5">
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-400 font-bold">Jalur</p>
                            <p class="text-3xl font-black mt-2">{{ $period?->tracks?->count() ?? 0 }}</p>
                        </div>
                        <div class="glass rounded-[24px] p-5">
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-400 font-bold">Jurusan</p>
                            <p class="text-3xl font-black mt-2">{{ $programsCount }}</p>
                        </div>
                        <div class="glass rounded-[24px] p-5">
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-400 font-bold">Lulus</p>
                            <p class="text-3xl font-black mt-2">{{ $acceptedCount }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16 bg-white border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="rounded-[24px] bg-slate-50 p-6 border border-slate-100">
                <p class="text-xs uppercase tracking-[0.25em] text-slate-400 font-bold">Pendaftaran</p>
                <h3 class="text-lg font-black text-slate-900 mt-2">{{ $period?->tanggal_mulai_pendaftaran?->translatedFormat('d M Y') ?? '-' }}</h3>
                <p class="text-sm text-slate-500 mt-2">sampai {{ $period?->tanggal_selesai_pendaftaran?->translatedFormat('d M Y') ?? '-' }}</p>
            </div>
            <div class="rounded-[24px] bg-slate-50 p-6 border border-slate-100">
                <p class="text-xs uppercase tracking-[0.25em] text-slate-400 font-bold">Pengumuman</p>
                <h3 class="text-lg font-black text-slate-900 mt-2">{{ $period?->tanggal_pengumuman?->translatedFormat('d M Y') ?? 'Akan diumumkan' }}</h3>
                <p class="text-sm text-slate-500 mt-2">{{ $period?->isAnnouncementPublished() ? 'Sudah dipublikasikan' : 'Menunggu publikasi resmi' }}</p>
            </div>
            <div class="rounded-[24px] bg-slate-50 p-6 border border-slate-100">
                <p class="text-xs uppercase tracking-[0.25em] text-slate-400 font-bold">Daftar Ulang</p>
                <h3 class="text-lg font-black text-slate-900 mt-2">{{ $period?->tanggal_mulai_daftar_ulang?->translatedFormat('d M Y') ?? '-' }}</h3>
                <p class="text-sm text-slate-500 mt-2">sampai {{ $period?->tanggal_selesai_daftar_ulang?->translatedFormat('d M Y') ?? '-' }}</p>
            </div>
            <div class="rounded-[24px] bg-slate-50 p-6 border border-slate-100">
                <p class="text-xs uppercase tracking-[0.25em] text-slate-400 font-bold">Pendaftar</p>
                <h3 class="text-lg font-black text-slate-900 mt-2">{{ $applicationsCount }}</h3>
                <p class="text-sm text-slate-500 mt-2">data masuk pada periode aktif</p>
            </div>
        </div>
    </section>

    @if($period)
    <section class="py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <div class="bg-white rounded-[28px] border border-slate-100 p-7 shadow-sm">
                    <p class="text-xs uppercase tracking-[0.25em] text-blue-500 font-bold">Langkah 1</p>
                    <h3 class="text-xl font-black text-slate-900 mt-3">Pelajari Informasi</h3>
                    <p class="text-sm text-slate-500 mt-3 leading-relaxed">Pahami jadwal, jalur, kuota, dan syarat berkas sebelum masuk ke formulir. Ini meminimalkan revisi saat verifikasi berkas.</p>
                </div>
                <div class="bg-white rounded-[28px] border border-slate-100 p-7 shadow-sm">
                    <p class="text-xs uppercase tracking-[0.25em] text-blue-500 font-bold">Langkah 2</p>
                    <h3 class="text-xl font-black text-slate-900 mt-3">Isi Formulir</h3>
                    <p class="text-sm text-slate-500 mt-3 leading-relaxed">Buka route formulir khusus untuk mengisi identitas, pilihan jurusan, dan upload berkas secara lengkap dan terstruktur.</p>
                    <a href="{{ route('ppdb.form', $periodQuery) }}" class="inline-flex mt-5 text-sm font-bold text-blue-600 hover:text-blue-700">Buka Formulir</a>
                </div>
                <div class="bg-white rounded-[28px] border border-slate-100 p-7 shadow-sm">
                    <p class="text-xs uppercase tracking-[0.25em] text-blue-500 font-bold">Langkah 3</p>
                    <h3 class="text-xl font-black text-slate-900 mt-3">Pantau Hasil dan Daftar Ulang</h3>
                    <p class="text-sm text-slate-500 mt-3 leading-relaxed">Gunakan portal status untuk melihat progres verifikasi dan hasil resmi. Peserta yang lulus bisa lanjut ke daftar ulang pada route khusus.</p>
                </div>
            </div>

            <div>
                <div class="flex items-end justify-between gap-4 flex-wrap mb-8">
                    <div>
                        <span class="inline-flex px-4 py-1.5 rounded-full bg-blue-50 text-blue-600 text-xs font-bold uppercase tracking-[0.25em] mb-4">Jalur & Kuota</span>
                        <h2 class="text-3xl lg:text-5xl font-black tracking-tight text-slate-900">Pilihan <span class="text-gradient">Jalur Pendaftaran</span></h2>
                    </div>
                    <a href="{{ route('ppdb.form', $periodQuery) }}" class="text-sm font-bold text-blue-600 hover:text-blue-700">Lanjut ke Form Pendaftaran</a>
                </div>
                <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                    @foreach($period->tracks as $track)
                    <div class="bg-white rounded-[28px] border border-slate-100 p-7 shadow-sm">
                        <p class="text-xs uppercase tracking-[0.25em] text-slate-400 font-bold">{{ $track->slug }}</p>
                        <h3 class="text-xl font-black text-slate-900 mt-3">{{ $track->nama_jalur }}</h3>
                        <p class="text-sm text-slate-500 mt-3 leading-relaxed">{{ $track->deskripsi ?: 'Jalur pendaftaran yang disiapkan untuk mendukung seleksi administrasi dan pemeringkatan.' }}</p>
                        <div class="mt-6 space-y-3">
                            @foreach($period->quotas->where('track_id', $track->id) as $quota)
                            <div class="flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-3">
                                <span class="text-sm font-semibold text-slate-700">{{ $quota->programKeahlian->nama_jurusan }}</span>
                                <span class="text-sm font-black text-slate-900">{{ $quota->kuota }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                <div class="bg-white rounded-[32px] border border-slate-100 shadow-sm p-8">
                    <p class="text-xs uppercase tracking-[0.25em] text-slate-400 font-bold">Yang Perlu Disiapkan</p>
                    <h3 class="text-2xl font-black text-slate-900 mt-3">Persyaratan Umum</h3>
                    <ul class="mt-6 space-y-4 text-sm text-slate-600">
                        <li>Data identitas siswa sesuai dokumen resmi.</li>
                        <li>Nomor HP siswa atau orang tua yang aktif.</li>
                        <li>Scan atau foto KK, akta lahir, rapor, pas foto, dan SKL bila tersedia.</li>
                        <li>Pilihan jurusan utama dan cadangan yang sudah dipertimbangkan.</li>
                    </ul>
                </div>
                <div class="bg-slate-900 text-white rounded-[32px] p-8 noise relative overflow-hidden">
                    <div class="absolute inset-0 bg-mesh-hero opacity-45"></div>
                    <div class="relative">
                        <p class="text-xs uppercase tracking-[0.25em] text-blue-300 font-bold">Akses Cepat</p>
                        <h3 class="text-2xl font-black mt-3">Portal Publik PPDB</h3>
                        <div class="mt-6 space-y-4">
                            <a href="{{ route('ppdb.form', $periodQuery) }}" class="flex items-center justify-between rounded-2xl glass px-5 py-4 font-bold hover:bg-white/10 transition">
                                <span>Form Pendaftaran</span>
                                <span>01</span>
                            </a>
                            <a href="{{ route('ppdb.status') }}" class="flex items-center justify-between rounded-2xl glass px-5 py-4 font-bold hover:bg-white/10 transition">
                                <span>Cek Status</span>
                                <span>02</span>
                            </a>
                            <a href="{{ route('ppdb.daftar-ulang') }}" class="flex items-center justify-between rounded-2xl glass px-5 py-4 font-bold hover:bg-white/10 transition">
                                <span>Daftar Ulang</span>
                                <span>03</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @else
    <section class="py-24 bg-slate-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="bg-white rounded-[32px] border border-slate-100 shadow-sm p-10">
                <span class="inline-flex px-4 py-1.5 rounded-full bg-amber-50 text-amber-600 text-xs font-bold uppercase tracking-[0.25em] mb-4">Belum Aktif</span>
                <h2 class="text-3xl font-black text-slate-900">Periode PPDB Belum Dipublikasikan</h2>
                <p class="text-slate-500 mt-4 max-w-2xl mx-auto">Panitia belum membuka periode pendaftaran aktif. Silakan cek kembali halaman ini untuk informasi terbaru dari panitia PPDB sekolah.</p>
                <a href="{{ route('ppdb.status') }}" class="inline-flex mt-8 px-6 py-3 rounded-2xl bg-slate-900 text-white font-bold hover:bg-slate-800 transition">Buka Halaman Cek Status</a>
            </div>
        </div>
    </section>
    @endif
</div>