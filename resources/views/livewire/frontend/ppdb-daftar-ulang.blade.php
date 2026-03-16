<div>
    <section class="relative overflow-hidden bg-slate-950 text-white py-24 noise">
        <div class="absolute inset-0 bg-mesh-hero opacity-70"></div>
        <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-flex px-4 py-1.5 rounded-full glass text-xs font-bold uppercase tracking-[0.3em] text-blue-300 mb-6">Daftar Ulang</span>
            <h1 class="text-4xl lg:text-6xl font-black tracking-tight leading-tight">Konfirmasi <span class="text-gradient">Daftar Ulang</span></h1>
            <p class="text-slate-300 max-w-2xl mx-auto mt-4">Peserta yang sudah dinyatakan lulus secara resmi dapat mengirim konfirmasi daftar ulang melalui portal ini selama jadwal masih aktif.</p>
        </div>
    </section>

    <section class="py-20 bg-slate-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white rounded-[24px] border border-slate-100 p-6 shadow-sm">
                    <p class="text-xs uppercase tracking-[0.25em] text-slate-400 font-bold">Syarat</p>
                    <h3 class="text-lg font-black text-slate-900 mt-3">Sudah Lulus Resmi</h3>
                    <p class="text-sm text-slate-500 mt-2">Daftar ulang hanya tersedia bagi peserta yang hasil resminya sudah berstatus lulus.</p>
                </div>
                <div class="bg-white rounded-[24px] border border-slate-100 p-6 shadow-sm">
                    <p class="text-xs uppercase tracking-[0.25em] text-slate-400 font-bold">Aksi</p>
                    <h3 class="text-lg font-black text-slate-900 mt-3">Kirim Konfirmasi</h3>
                    <p class="text-sm text-slate-500 mt-2">Masukkan nomor pendaftaran lalu kirim catatan konfirmasi kehadiran untuk diverifikasi panitia.</p>
                </div>
                <div class="bg-white rounded-[24px] border border-slate-100 p-6 shadow-sm">
                    <p class="text-xs uppercase tracking-[0.25em] text-slate-400 font-bold">Lanjutan</p>
                    <h3 class="text-lg font-black text-slate-900 mt-3">Menunggu Verifikasi</h3>
                    <p class="text-sm text-slate-500 mt-2">Setelah dikirim, status daftar ulang akan berubah saat panitia menyetujui atau meminta penyesuaian.</p>
                </div>
            </div>

            <div class="bg-white rounded-[28px] border border-slate-100 shadow-sm p-8 md:p-10">
                <form wire:submit="search" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Nomor Pendaftaran</label>
                        <input wire:model="nomor_pendaftaran" type="text" placeholder="PPDB-2026-0001" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 outline-none">
                        @error('nomor_pendaftaran') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Lahir</label>
                        <input wire:model="tanggal_lahir" type="date" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 outline-none">
                        @error('tanggal_lahir') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="md:col-span-3">
                        <button type="submit" class="px-6 py-3 bg-slate-900 text-white text-sm font-bold rounded-2xl hover:bg-slate-800 transition">Cari Data Lulus</button>
                    </div>
                </form>
            </div>

            @if($searched && $result)
            <div class="bg-white rounded-[28px] border border-slate-100 shadow-sm p-8 space-y-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.25em] text-slate-400">Peserta</p>
                        <h2 class="text-2xl font-black text-slate-900 mt-2">{{ $result->nama_lengkap }}</h2>
                        <p class="text-sm text-slate-500 mt-1">{{ $result->nomor_pendaftaran }} · {{ $result->programDiterima?->nama_jurusan ?? 'Belum ada program final' }}</p>
                    </div>
                    <span class="px-3 py-1.5 rounded-full text-xs font-bold {{ $result->status_daftar_ulang === 'verified' ? 'bg-emerald-50 text-emerald-700' : ($result->status_daftar_ulang === 'submitted' ? 'bg-blue-50 text-blue-700' : ($result->status_daftar_ulang === 'rejected' ? 'bg-red-50 text-red-700' : 'bg-amber-50 text-amber-700')) }}">
                        {{ str($result->status_daftar_ulang)->replace('_', ' ')->title() }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="rounded-3xl bg-slate-50 p-6 text-sm text-slate-700 space-y-2">
                        <p><span class="font-semibold">Jalur:</span> {{ $result->track->nama_jalur }}</p>
                        <p><span class="font-semibold">Hasil Seleksi:</span> {{ str($result->hasil_seleksi)->replace('_', ' ')->title() }}</p>
                        <p><span class="font-semibold">Jadwal:</span> {{ $result->period->tanggal_mulai_daftar_ulang?->translatedFormat('d M Y') }} - {{ $result->period->tanggal_selesai_daftar_ulang?->translatedFormat('d M Y') }}</p>
                    </div>
                    <div class="rounded-3xl bg-slate-50 p-6 text-sm text-slate-700">
                        <p class="font-semibold mb-2">Catatan Panitia</p>
                        <p class="leading-relaxed">{{ $result->catatan_daftar_ulang ?: 'Belum ada catatan daftar ulang.' }}</p>
                    </div>
                </div>

                @if($result->hasil_seleksi === 'passed' && $result->period->isAnnouncementPublished() && $result->status_daftar_ulang !== 'verified')
                <form wire:submit="submitReRegistration" class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Catatan Daftar Ulang</label>
                        <textarea wire:model="catatan_daftar_ulang" rows="4" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 outline-none" placeholder="Tulis konfirmasi kehadiran, rencana kedatangan, atau catatan penting untuk panitia."></textarea>
                        @error('catatan_daftar_ulang') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <button type="submit" class="px-6 py-3 bg-emerald-600 text-white text-sm font-bold rounded-2xl hover:bg-emerald-700 transition">Kirim Konfirmasi Daftar Ulang</button>
                </form>
                @endif

                @if($submitted)
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 text-sm font-semibold text-emerald-700">
                    Konfirmasi daftar ulang sudah diterima. Panitia akan memverifikasi data Anda.
                </div>
                @endif
            </div>
            @elseif($searched)
            <div class="rounded-[28px] bg-white border border-red-100 p-8 text-center text-red-600 font-semibold shadow-sm">
                Data pendaftar tidak ditemukan. Periksa kembali nomor pendaftaran dan tanggal lahir.
            </div>
            @endif
        </div>
    </section>
</div>