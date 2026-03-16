<div>
    {{-- ============================================ --}}
    {{-- HERO --}}
    {{-- ============================================ --}}
    <section class="relative py-28 overflow-hidden bg-slate-950 noise">
        <div class="absolute inset-0">
            <div class="absolute top-[-20%] left-[-10%] w-[400px] h-[400px] bg-blue-600/20 rounded-full blur-[100px] animate-blob"></div>
            <div class="absolute bottom-[-10%] right-[-5%] w-[300px] h-[300px] bg-indigo-600/15 rounded-full blur-[80px] animate-blob" style="animation-delay:2s"></div>
            <div class="absolute top-[40%] right-[15%] w-[200px] h-[200px] bg-purple-500/10 rounded-full blur-[80px] animate-float-slow"></div>
        </div>
        <div class="absolute inset-0 opacity-[0.03]" style="background-image: linear-gradient(rgba(255,255,255,0.1) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.1) 1px, transparent 1px); background-size: 60px 60px;"></div>
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-[20%] left-[8%] w-16 h-16 border border-white/10 rounded-2xl rotate-12 animate-float"></div>
            <div class="absolute bottom-[20%] right-[10%] w-12 h-12 border border-blue-400/20 rounded-full animate-float-reverse"></div>
            <div class="absolute top-[30%] right-[30%] w-3 h-3 bg-blue-400/40 rounded-full animate-float" style="animation-delay:1s"></div>
        </div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-blue-300 glass rounded-full uppercase tracking-wider mb-6 animate-fade-up">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                Tentang Kami
            </span>
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight text-white mb-6 leading-[0.95] animate-fade-up delay-100">
                Profil <span class="text-gradient">Sekolah</span>
            </h1>
            <p class="text-lg text-slate-400 max-w-2xl animate-fade-up delay-200">Mengenal lebih dekat SMK Negeri 1 Kolaka, visi misi, serta komitmen kami dalam mencetak generasi unggul.</p>
        </div>
    </section>

    @if($profil)
    {{-- ============================================ --}}
    {{-- SAMBUTAN KEPSEK --}}
    {{-- ============================================ --}}
    @if($profil->teks_sambutan_kepsek)
    <section class="py-28 bg-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-blue-50 rounded-full blur-[120px] -translate-y-1/2 translate-x-1/2"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-16 items-center">
                <div class="lg:col-span-2 relative">
                    <div class="relative z-10">
                        @if($profil->foto_kepsek)
                            <img src="{{ Storage::url($profil->foto_kepsek) }}" class="rounded-[32px] w-full object-cover max-h-[500px] shadow-2xl" alt="Kepala Sekolah">
                        @else
                            <div class="aspect-[3/4] rounded-[32px] bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-24 h-24 text-white/10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            </div>
                        @endif
                    </div>
                    <div class="absolute -top-3 -left-3 w-full h-full rounded-[32px] border-2 border-blue-500/20 -z-0"></div>
                    <div class="absolute -bottom-3 -right-3 w-full h-full rounded-[32px] border-2 border-indigo-500/10 -z-0"></div>
                </div>
                <div class="lg:col-span-3">
                    <span class="inline-block px-4 py-1.5 text-xs font-bold text-blue-600 bg-blue-50 rounded-full uppercase tracking-wider mb-4">Sambutan Kepala Sekolah</span>
                    <h2 class="text-3xl lg:text-4xl font-black tracking-tight text-slate-900 mb-8 leading-[1.1]">
                        Selamat Datang di<br><span class="text-gradient">SMKN 1 Kolaka</span>
                    </h2>
                    <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed">
                        {!! nl2br(e($profil->teks_sambutan_kepsek)) !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- ============================================ --}}
    {{-- VISI & MISI --}}
    {{-- ============================================ --}}
    <section class="py-28 bg-slate-50 relative overflow-hidden">
        <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-indigo-50 rounded-full blur-[120px] translate-y-1/2 -translate-x-1/2"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-1.5 text-xs font-bold text-indigo-600 bg-indigo-50 rounded-full uppercase tracking-wider mb-4">Visi & Misi</span>
                <h2 class="text-3xl lg:text-4xl font-black tracking-tight text-slate-900">Arah & <span class="text-gradient">Tujuan Kami</span></h2>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="bg-white p-10 rounded-[28px] border border-slate-100 card-hover">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-blue-500/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-4">Visi</h3>
                    <div class="text-slate-600 leading-relaxed">
                        {!! nl2br(e($profil->visi_teks ?? 'Belum diatur')) !!}
                    </div>
                </div>
                <div class="bg-white p-10 rounded-[28px] border border-slate-100 card-hover">
                    <div class="w-14 h-14 bg-gradient-to-br from-indigo-400 to-indigo-600 rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-indigo-500/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" /></svg>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-4">Misi</h3>
                    <div class="text-slate-600 leading-relaxed">
                        {!! nl2br(e($profil->misi_teks ?? 'Belum diatur')) !!}
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================================ --}}
    {{-- INFORMASI SEKOLAH --}}
    {{-- ============================================ --}}
    <section class="py-28 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-14">
                <span class="inline-block px-4 py-1.5 text-xs font-bold text-emerald-600 bg-emerald-50 rounded-full uppercase tracking-wider mb-4">Informasi</span>
                <h2 class="text-3xl lg:text-4xl font-black tracking-tight text-slate-900">Data <span class="text-gradient">Sekolah</span></h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @if($profil->npsn)
                <div class="bg-slate-50 rounded-[24px] p-7 card-hover border border-slate-100">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" /></svg>
                    </div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">NPSN</span>
                    <p class="text-lg font-black text-slate-900 mt-1">{{ $profil->npsn }}</p>
                </div>
                @endif
                @if($profil->alamat_lengkap)
                <div class="bg-slate-50 rounded-[24px] p-7 card-hover border border-slate-100">
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-400 to-indigo-600 rounded-xl flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /></svg>
                    </div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Alamat</span>
                    <p class="text-sm font-bold text-slate-900 mt-1 leading-relaxed">{{ $profil->alamat_lengkap }}</p>
                </div>
                @endif
                @if($profil->nomor_telepon)
                <div class="bg-slate-50 rounded-[24px] p-7 card-hover border border-slate-100">
                    <div class="w-10 h-10 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-xl flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                    </div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Telepon</span>
                    <p class="text-lg font-black text-slate-900 mt-1">{{ $profil->nomor_telepon }}</p>
                </div>
                @endif
                @if($profil->email_resmi)
                <div class="bg-slate-50 rounded-[24px] p-7 card-hover border border-slate-100">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    </div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Email</span>
                    <p class="text-sm font-black text-slate-900 mt-1">{{ $profil->email_resmi }}</p>
                </div>
                @endif
            </div>
        </div>
    </section>
    @else
    <section class="py-28 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-slate-400 text-lg">Profil sekolah belum diatur. Silakan lengkapi melalui panel admin.</p>
        </div>
    </section>
    @endif

    {{-- ============================================ --}}
    {{-- TENAGA PENGAJAR --}}
    {{-- ============================================ --}}
    @if($pegawai->count() > 0)
    <section class="py-28 bg-slate-50 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-blue-50 rounded-full blur-[120px] -translate-y-1/2 translate-x-1/2"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-1.5 text-xs font-bold text-purple-600 bg-purple-50 rounded-full uppercase tracking-wider mb-4">Tim Kami</span>
                <h2 class="text-3xl lg:text-4xl font-black tracking-tight text-slate-900">Tenaga Pendidik & <span class="text-gradient">Kependidikan</span></h2>
                <p class="text-slate-500 mt-4 max-w-xl mx-auto">Tim profesional yang berdedikasi untuk masa depan siswa.</p>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($pegawai as $p)
                <div class="group bg-white rounded-[24px] border border-slate-100 overflow-hidden text-center card-hover">
                    <div class="aspect-square bg-slate-100 overflow-hidden">
                        @if($p->foto_path)
                            <img src="{{ Storage::url($p->foto_path) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" alt="{{ $p->nama_lengkap }}">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-slate-50 to-slate-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            </div>
                        @endif
                    </div>
                    <div class="p-5">
                        <h4 class="font-bold text-slate-900 text-sm">{{ $p->nama_lengkap }}</h4>
                        <p class="text-xs text-slate-400 mt-1">{{ $p->jabatan }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
</div>
