<div>
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
                        <span class="block text-gradient">Peserta Didik Baru</span>
                    </h1>
                    <p class="mt-6 text-lg text-slate-300 max-w-2xl leading-relaxed">
                        Halaman ini menjadi titik utama informasi PPDB: jadwal, jalur, kuota, alur seleksi, pengumuman resmi, hingga akses menuju formulir pendaftaran dan daftar ulang.
                    </p>
                    <div class="flex flex-wrap gap-4 mt-10">
                        @if($period)
                        <a href="#form-ppdb" class="px-7 py-4 rounded-2xl bg-white text-slate-900 font-bold hover:-translate-y-1 transition">Daftar Sekarang</a>
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
                        <p class="text-xs uppercase tracking-[0.25em] text-slate-400 font-bold">Periode Aktif</p>
                        <h2 class="text-2xl font-black mt-3">{{ $period?->nama_periode ?? 'Belum Dibuka' }}</h2>
                        <p class="text-sm text-slate-300 mt-1">{{ $period?->tahun_ajaran ?? 'Menunggu publikasi panitia' }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="glass rounded-[24px] p-5">
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-400 font-bold">Jalur</p>
                            <p class="text-3xl font-black mt-2">{{ $period?->tracks?->count() ?? 0 }}</p>
                        </div>
                        <div class="glass rounded-[24px] p-5">
                            <p class="text-xs uppercase tracking-[0.2em] text-slate-400 font-bold">Pendaftar</p>
                            <p class="text-3xl font-black mt-2">{{ $applicationsCount }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16 bg-white border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="rounded-[24px] bg-slate-50 p-6 border border-slate-100">
                <p class="text-xs uppercase tracking-[0.25em] text-slate-400 font-bold">Pendaftaran</p>
                <h3 class="text-lg font-black text-slate-900 mt-2">{{ $period?->tanggal_mulai_pendaftaran?->translatedFormat('d M Y') ?? '-' }} - {{ $period?->tanggal_selesai_pendaftaran?->translatedFormat('d M Y') ?? '-' }}</h3>
            </div>
            <div class="rounded-[24px] bg-slate-50 p-6 border border-slate-100">
                <p class="text-xs uppercase tracking-[0.25em] text-slate-400 font-bold">Pengumuman</p>
                <h3 class="text-lg font-black text-slate-900 mt-2">{{ $period?->tanggal_pengumuman?->translatedFormat('d M Y') ?? 'Akan diumumkan' }}</h3>
                <p class="text-sm text-slate-500 mt-2">{{ $period?->isAnnouncementPublished() ? 'Hasil resmi sudah dipublikasikan.' : 'Menunggu publikasi resmi panitia.' }}</p>
            </div>
            <div class="rounded-[24px] bg-slate-50 p-6 border border-slate-100">
                <p class="text-xs uppercase tracking-[0.25em] text-slate-400 font-bold">Daftar Ulang</p>
                <h3 class="text-lg font-black text-slate-900 mt-2">{{ $period?->tanggal_mulai_daftar_ulang?->translatedFormat('d M Y') ?? '-' }} {{ $period?->tanggal_selesai_daftar_ulang ? ' - ' . $period->tanggal_selesai_daftar_ulang->translatedFormat('d M Y') : '' }}</h3>
            </div>
        </div>
    </section>

    @if($period)
    <section class="py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
            <div>
                <div class="flex items-end justify-between gap-4 flex-wrap mb-8">
                    <div>
                        <span class="inline-flex px-4 py-1.5 rounded-full bg-blue-50 text-blue-600 text-xs font-bold uppercase tracking-[0.25em] mb-4">Jalur & Kuota</span>
                        <h2 class="text-3xl lg:text-5xl font-black tracking-tight text-slate-900">Struktur Awal <span class="text-gradient">PPDB</span></h2>
                    </div>
                    <a href="{{ route('ppdb.status') }}" class="text-sm font-bold text-blue-600 hover:text-blue-700">Sudah daftar? Cek status</a>
                </div>
                <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                    @foreach($period->tracks as $track)
                    <div class="bg-white rounded-[28px] border border-slate-100 p-7 shadow-sm">
                        <p class="text-xs uppercase tracking-[0.25em] text-slate-400 font-bold">{{ $track->slug }}</p>
                        <h3 class="text-xl font-black text-slate-900 mt-3">{{ $track->nama_jalur }}</h3>
                        <p class="text-sm text-slate-500 mt-3 leading-relaxed">{{ $track->deskripsi ?: 'Jalur pendaftaran yang disiapkan agar modul fase 1 ini bisa langsung berkembang ke fase seleksi dan scoring.' }}</p>
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

            <div id="form-ppdb" class="grid grid-cols-1 xl:grid-cols-5 gap-8">
                <div class="xl:col-span-2 space-y-6">
                    <div class="bg-slate-900 text-white rounded-[32px] p-8 noise relative overflow-hidden">
                        <div class="absolute inset-0 bg-mesh-hero opacity-50"></div>
                        <div class="relative">
                            <span class="inline-flex px-4 py-1.5 rounded-full glass text-xs font-bold uppercase tracking-[0.25em] text-blue-300 mb-5">Panduan Singkat</span>
                            <h3 class="text-2xl font-black leading-tight">Sebelum Mengisi Form</h3>
                            <ul class="mt-6 space-y-4 text-sm text-slate-300">
                                <li>Siapkan data siswa, data orang tua, dan asal sekolah.</li>
                                <li>Siapkan file KK, akta lahir, rapor, pas foto, dan SKL bila ada.</li>
                                <li>Pilih jalur pendaftaran dan jurusan utama dengan cermat.</li>
                                <li>Setelah submit, simpan nomor pendaftaran untuk cek status.</li>
                            </ul>
                        </div>
                    </div>
                    @if($submittedNumber)
                    <div class="rounded-[28px] border border-emerald-200 bg-emerald-50 p-6">
                        <p class="text-xs uppercase tracking-[0.25em] text-emerald-600 font-bold">Pendaftaran Berhasil</p>
                        <h4 class="text-2xl font-black text-emerald-800 mt-3">{{ $submittedNumber }}</h4>
                        <p class="text-sm text-emerald-700 mt-3">Nomor pendaftaran Anda berhasil dibuat. Simpan nomor ini untuk cek status di halaman status PPDB.</p>
                    </div>
                    @endif
                </div>

                <div class="xl:col-span-3 bg-white rounded-[32px] border border-slate-100 shadow-sm p-6 md:p-8">
                    <div class="mb-8">
                        <p class="text-xs uppercase tracking-[0.25em] text-slate-400 font-bold">Form Pendaftaran</p>
                        <h3 class="text-2xl md:text-3xl font-black text-slate-900 mt-3">Daftar PPDB Online</h3>
                    </div>

                    <form wire:submit="submitApplication" class="space-y-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Lengkap *</label>
                                <input wire:model="nama_lengkap" type="text" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10">
                                @error('nama_lengkap') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">NISN</label>
                                <input wire:model="nisn" type="text" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10">
                                @error('nisn') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">NIK</label>
                                <input wire:model="nik" type="text" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10">
                                @error('nik') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Jenis Kelamin *</label>
                                <select wire:model="jenis_kelamin" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10">
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Agama</label>
                                <input wire:model="agama" type="text" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Tempat Lahir *</label>
                                <input wire:model="tempat_lahir" type="text" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10">
                                @error('tempat_lahir') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Lahir *</label>
                                <input wire:model="tanggal_lahir" type="date" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10">
                                @error('tanggal_lahir') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Nomor HP *</label>
                                <input wire:model="nomor_hp" type="text" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10">
                                @error('nomor_hp') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Email</label>
                                <input wire:model="email" type="email" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10">
                                @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Alamat Lengkap *</label>
                                <textarea wire:model="alamat_lengkap" rows="3" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10"></textarea>
                                @error('alamat_lengkap') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Asal Sekolah *</label>
                                <input wire:model="asal_sekolah" type="text" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10">
                                @error('asal_sekolah') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Nilai Rata-rata</label>
                                <input wire:model="nilai_rata_rata" type="number" step="0.01" min="0" max="100" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10">
                                @error('nilai_rata_rata') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Jalur Pendaftaran *</label>
                                <select wire:model="track_id" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10">
                                    <option value="">Pilih Jalur</option>
                                    @foreach($period->tracks as $track)
                                    <option value="{{ $track->id }}">{{ $track->nama_jalur }}</option>
                                    @endforeach
                                </select>
                                @error('track_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Pilihan Jurusan 1 *</label>
                                <select wire:model="pilihan_program_1_id" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10">
                                    <option value="">Pilih Jurusan</option>
                                    @foreach($programs as $program)
                                    <option value="{{ $program->id }}">{{ $program->nama_jurusan }}</option>
                                    @endforeach
                                </select>
                                @error('pilihan_program_1_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Pilihan Jurusan 2</label>
                                <select wire:model="pilihan_program_2_id" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10">
                                    <option value="">Tidak memilih cadangan</option>
                                    @foreach($programs as $program)
                                    <option value="{{ $program->id }}">{{ $program->nama_jurusan }}</option>
                                    @endforeach
                                </select>
                                @error('pilihan_program_2_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Ayah</label>
                                <input wire:model="nama_ayah" type="text" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Pekerjaan Ayah</label>
                                <input wire:model="pekerjaan_ayah" type="text" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Ibu</label>
                                <input wire:model="nama_ibu" type="text" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Pekerjaan Ibu</label>
                                <input wire:model="pekerjaan_ibu" type="text" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Nomor HP Orang Tua</label>
                                <input wire:model="nomor_hp_orang_tua" type="text" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Catatan Tambahan</label>
                            <textarea wire:model="catatan_pendaftar" rows="3" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10"></textarea>
                        </div>

                        <div>
                            <p class="text-sm font-semibold text-slate-700 mb-4">Upload Berkas</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-600 mb-2">Kartu Keluarga *</label>
                                    <input wire:model="file_kk" type="file" class="w-full text-sm text-slate-500">
                                    @error('file_kk') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-600 mb-2">Akta Kelahiran *</label>
                                    <input wire:model="file_akta" type="file" class="w-full text-sm text-slate-500">
                                    @error('file_akta') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-600 mb-2">Rapor / Nilai *</label>
                                    <input wire:model="file_rapor" type="file" class="w-full text-sm text-slate-500">
                                    @error('file_rapor') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-600 mb-2">Pas Foto *</label>
                                    <input wire:model="file_pas_foto" type="file" class="w-full text-sm text-slate-500">
                                    @error('file_pas_foto') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-slate-600 mb-2">Surat Keterangan Lulus</label>
                                    <input wire:model="file_skl" type="file" class="w-full text-sm text-slate-500">
                                    @error('file_skl') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        @error('period') <p class="text-sm text-red-600">{{ $message }}</p> @enderror

                        <div class="flex flex-wrap items-center gap-4 pt-2">
                            <button type="submit" class="px-7 py-4 rounded-2xl bg-blue-600 text-white font-bold hover:bg-blue-700 transition">Kirim Pendaftaran</button>
                            <a href="{{ route('ppdb.status') }}" class="text-sm font-bold text-slate-600 hover:text-blue-600">Sudah punya nomor pendaftaran?</a>
                        </div>
                    </form>
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
                <p class="text-slate-500 mt-4 max-w-2xl mx-auto">Panitia belum membuka periode pendaftaran aktif. Fondasi modul PPDB sudah siap, tetapi periode dan jalur belum dipublikasikan oleh admin.</p>
                <a href="{{ route('ppdb.status') }}" class="inline-flex mt-8 px-6 py-3 rounded-2xl bg-slate-900 text-white font-bold hover:bg-slate-800 transition">Buka Halaman Cek Status</a>
            </div>
        </div>
    </section>
    @endif
</div>
