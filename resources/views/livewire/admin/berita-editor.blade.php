<section class="space-y-6"
         x-data="beritaEditor({ wire: $wire, draftKey: 'berita-editor-{{ $editId ?? 'new' }}' })"
         x-init="init()"
         x-on:berita-inline-image-uploaded.window="insertUploadedImage($event.detail.url)">
    <div class="rounded-[1.75rem] border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.25em] text-blue-500">Ruang Redaksi</p>
                <h1 class="mt-2 text-2xl font-black text-slate-900 dark:text-white">{{ $isEditing ? 'Edit Berita' : 'Tambah Berita' }}</h1>
                <p class="mt-2 max-w-3xl text-sm text-slate-500 dark:text-slate-400">Editor berada di halaman tersendiri agar proses menulis, upload media, dan preview artikel lebih fokus tanpa mengganggu daftar berita.</p>
                @if($sourceTitle)
                    <div class="mt-3 inline-flex items-center gap-2 rounded-full bg-blue-50 px-4 py-2 text-xs font-semibold text-blue-700 dark:bg-blue-950/40 dark:text-blue-300">
                        Template dari: {{ $sourceTitle }}
                    </div>
                @endif
            </div>
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-4 lg:w-[28rem]">
                <button type="button" @click="mode = 'write'; focusEditor()" :class="mode === 'write' ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20' : 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300'" class="rounded-2xl px-4 py-3 text-sm font-semibold transition">Tulis</button>
                <button type="button" @click="mode = 'preview'" :class="mode === 'preview' ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20' : 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300'" class="rounded-2xl px-4 py-3 text-sm font-semibold transition">Preview</button>
                <button type="button" @click="templateLead()" class="rounded-2xl bg-amber-50 px-4 py-3 text-sm font-semibold text-amber-700 transition hover:bg-amber-100 dark:bg-amber-950/40 dark:text-amber-300">Template</button>
                <a href="{{ route('admin.berita') }}" class="rounded-2xl bg-slate-100 px-4 py-3 text-center text-sm font-semibold text-slate-700 transition hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700">Kembali</a>
            </div>
        </div>
    </div>

    <form wire:submit="save" class="space-y-6">
        <div class="grid gap-6 2xl:grid-cols-[1.15fr_0.85fr]">
            <div class="space-y-4">
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Judul *</label>
                    <input wire:model="judul" type="text" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 dark:border-slate-700 dark:bg-slate-800 dark:text-white" placeholder="Contoh: Siswa SMKN 1 Kolaka Raih Prestasi Nasional">
                    @error('judul') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Kategori *</label>
                        <select wire:model="kategori_id" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                            <option value="">-- Pilih --</option>
                            @foreach($kategoriList as $kat)
                                <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                            @endforeach
                        </select>
                        @error('kategori_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Status</label>
                        <select wire:model="status_publikasi" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                            <option value="archived">Archived</option>
                        </select>
                    </div>
                </div>

                <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50/80 dark:border-slate-700 dark:bg-slate-950/60">
                    <div class="flex flex-wrap gap-2 border-b border-slate-200 px-4 py-3 dark:border-slate-700">
                        <button type="button" @click="block('h2', 'Subjudul utama')" class="rounded-xl bg-white px-3 py-2 text-xs font-bold text-slate-700 shadow-sm transition hover:bg-slate-100 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">H2</button>
                        <button type="button" @click="block('h3', 'Subjudul lanjutan')" class="rounded-xl bg-white px-3 py-2 text-xs font-bold text-slate-700 shadow-sm transition hover:bg-slate-100 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">H3</button>
                        <button type="button" @click="paragraph()" class="rounded-xl bg-white px-3 py-2 text-xs font-bold text-slate-700 shadow-sm transition hover:bg-slate-100 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">Paragraf</button>
                        <button type="button" @click="insert('<strong>', '</strong>', 'teks tebal')" class="rounded-xl bg-white px-3 py-2 text-xs font-bold text-slate-700 shadow-sm transition hover:bg-slate-100 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">Bold</button>
                        <button type="button" @click="insert('<em>', '</em>', 'teks miring')" class="rounded-xl bg-white px-3 py-2 text-xs font-bold text-slate-700 shadow-sm transition hover:bg-slate-100 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">Italic</button>
                        <button type="button" @click="bulletList()" class="rounded-xl bg-white px-3 py-2 text-xs font-bold text-slate-700 shadow-sm transition hover:bg-slate-100 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">Bullet List</button>
                        <button type="button" @click="numberedList()" class="rounded-xl bg-white px-3 py-2 text-xs font-bold text-slate-700 shadow-sm transition hover:bg-slate-100 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">Numbered List</button>
                        <button type="button" @click="quote()" class="rounded-xl bg-white px-3 py-2 text-xs font-bold text-slate-700 shadow-sm transition hover:bg-slate-100 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">Quote</button>
                        <button type="button" @click="link()" class="rounded-xl bg-white px-3 py-2 text-xs font-bold text-slate-700 shadow-sm transition hover:bg-slate-100 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">Link</button>
                        <button type="button" @click="$refs.inlineImageInput.click()" class="rounded-xl bg-white px-3 py-2 text-xs font-bold text-slate-700 shadow-sm transition hover:bg-slate-100 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">Upload Gambar</button>
                        <button type="button" @click="imageFromUrl()" class="rounded-xl bg-white px-3 py-2 text-xs font-bold text-slate-700 shadow-sm transition hover:bg-slate-100 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">URL Gambar</button>
                        <input x-ref="inlineImageInput" wire:model="inline_image" type="file" accept="image/*" class="hidden">
                    </div>

                    <div class="grid gap-0 xl:grid-cols-[1.15fr_0.85fr]">
                        <div class="border-b border-slate-200 p-4 dark:border-slate-700 xl:border-b-0 xl:border-r">
                            <div class="mb-3 flex items-center justify-between gap-3">
                                <label class="text-sm font-semibold text-slate-700 dark:text-slate-300">Editor Konten *</label>
                                <div class="text-right">
                                    <p class="text-[11px] uppercase tracking-[0.2em] text-slate-400" x-text="mode === 'write' ? 'Mode tulis aktif' : 'Mode preview aktif'"></p>
                                    <p class="mt-1 text-[11px] text-slate-400" x-text="savedLabel"></p>
                                </div>
                            </div>
                            <div class="mb-3 flex flex-wrap items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                                <span class="rounded-full bg-slate-100 px-3 py-1 dark:bg-slate-800"><span x-text="wordCount"></span> kata</span>
                                <span class="rounded-full bg-slate-100 px-3 py-1 dark:bg-slate-800"><span x-text="readingMinutes"></span> menit baca</span>
                                <span wire:loading.inline wire:target="inline_image" class="rounded-full bg-blue-50 px-3 py-1 text-blue-700 dark:bg-blue-950/40 dark:text-blue-300">Mengunggah gambar...</span>
                            </div>

                            <div class="mb-3 rounded-2xl border border-dashed transition"
                                 :class="isDragging ? 'border-blue-500 bg-blue-50/70 dark:bg-blue-950/20' : 'border-slate-300 bg-white/70 dark:border-slate-700 dark:bg-slate-900/70'"
                                 @dragenter.prevent="isDragging = true"
                                 @dragover.prevent="isDragging = true"
                                 @dragleave.prevent="isDragging = false"
                                 @drop.prevent="handleDroppedFiles($event)">
                                <div class="px-4 py-3 text-xs text-slate-500 dark:text-slate-400">
                                    Seret gambar ke area ini untuk upload langsung ke isi artikel, atau gunakan tombol upload di toolbar.
                                </div>
                                <textarea x-ref="editor" x-model="content" rows="16" x-show="mode === 'write'" class="w-full rounded-b-2xl border-0 bg-transparent px-4 py-3 font-mono text-sm leading-7 text-slate-900 outline-none focus:ring-0 dark:text-white" placeholder="Tulis isi berita di sini. Gunakan toolbar untuk menambahkan heading, daftar, tautan, atau gambar."></textarea>
                            </div>

                            <div x-show="mode === 'preview'" x-cloak class="prose prose-slate max-w-none rounded-2xl border border-slate-200 bg-white px-5 py-4 text-sm leading-7 text-slate-700 dark:prose-invert dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200">
                                <div x-show="content.trim().length === 0" class="rounded-xl border border-dashed border-slate-300 px-4 py-8 text-center text-sm text-slate-400 dark:border-slate-700 dark:text-slate-500">
                                    Preview akan tampil di sini setelah Anda mulai menulis isi berita.
                                </div>
                                <div x-show="content.trim().length > 0" x-html="content"></div>
                            </div>
                            @error('inline_image') <p class="mt-2 text-xs text-red-500">{{ $message }}</p> @enderror
                            @error('konten_html') <p class="mt-2 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <aside class="space-y-4 p-4">
                            <div class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-slate-200 dark:bg-slate-900 dark:ring-slate-700">
                                <p class="text-xs font-bold uppercase tracking-[0.25em] text-slate-400">Panduan Cepat</p>
                                <ul class="mt-3 space-y-2 text-sm text-slate-600 dark:text-slate-300">
                                    <li>Mulai dengan lead singkat yang menjelaskan inti berita.</li>
                                    <li>Gunakan subjudul untuk memecah topik panjang.</li>
                                    <li>Pakai daftar poin untuk prestasi, agenda, atau rincian kegiatan.</li>
                                    <li>Gunakan media internal agar aset tidak tercecer.</li>
                                </ul>
                            </div>

                            <div class="rounded-2xl bg-slate-900 p-4 text-slate-100 dark:bg-slate-950">
                                <p class="text-xs font-bold uppercase tracking-[0.25em] text-slate-400">Struktur Disarankan</p>
                                <div class="mt-3 space-y-2 text-sm leading-6 text-slate-300">
                                    <p><strong>1.</strong> Lead singkat</p>
                                    <p><strong>2.</strong> Latar belakang atau konteks</p>
                                    <p><strong>3.</strong> Poin utama kegiatan atau prestasi</p>
                                    <p><strong>4.</strong> Kutipan atau penutup</p>
                                </div>
                            </div>
                        </aside>
                    </div>
                </div>
            </div>

            <aside class="space-y-4">
                <div class="rounded-[1.5rem] border border-emerald-200 bg-gradient-to-br from-emerald-50 via-white to-cyan-50 p-4 shadow-sm dark:border-emerald-900/60 dark:from-emerald-950/40 dark:via-slate-950 dark:to-cyan-950/30">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.25em] text-emerald-600 dark:text-emerald-300">Asisten AI</p>
                            <h2 class="mt-2 text-lg font-black text-slate-900 dark:text-white">Buat Draft Berita Otomatis</h2>
                        </div>
                        <span class="rounded-full bg-white/80 px-3 py-1 text-[11px] font-semibold text-emerald-700 ring-1 ring-emerald-200 dark:bg-slate-900/80 dark:text-emerald-300 dark:ring-emerald-900/80">NVIDIA</span>
                    </div>

                    <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-slate-300">Masukkan brief kegiatan, prestasi, atau agenda. Hasil AI akan mengisi ulang judul dan isi editor saat ini.</p>

                    <div class="mt-4 space-y-3">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Brief untuk AI *</label>
                            <textarea wire:model="ai_prompt" rows="5" class="w-full rounded-2xl border border-emerald-200 bg-white/90 px-4 py-3 text-sm text-slate-900 outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 dark:border-emerald-900/70 dark:bg-slate-900/80 dark:text-white" placeholder="Contoh: Tim siswa jurusan TKJ meraih juara 1 lomba IoT tingkat provinsi. Sertakan nama kegiatan, capaian, pernyataan kepala sekolah, dan dampaknya bagi motivasi siswa."></textarea>
                            @error('ai_prompt') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Nada Penulisan</label>
                                <select wire:model="ai_tone" class="w-full rounded-2xl border border-emerald-200 bg-white/90 px-4 py-3 text-sm text-slate-900 outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 dark:border-emerald-900/70 dark:bg-slate-900/80 dark:text-white">
                                    <option value="formal">Formal Informatif</option>
                                    <option value="ringan">Ringan Informatif</option>
                                    <option value="seremoni">Formal Seremonial</option>
                                </select>
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-slate-700 dark:text-slate-300">Panjang Draft</label>
                                <select wire:model="ai_length" class="w-full rounded-2xl border border-emerald-200 bg-white/90 px-4 py-3 text-sm text-slate-900 outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 dark:border-emerald-900/70 dark:bg-slate-900/80 dark:text-white">
                                    <option value="singkat">Singkat</option>
                                    <option value="sedang">Sedang</option>
                                    <option value="panjang">Panjang</option>
                                </select>
                            </div>
                        </div>

                        <div class="rounded-2xl bg-white/80 px-4 py-3 text-xs leading-5 text-slate-500 ring-1 ring-emerald-100 dark:bg-slate-900/70 dark:text-slate-400 dark:ring-emerald-950/60">
                            Pilih kategori lebih dulu jika ingin AI menyesuaikan angle artikel. Gunakan fakta inti pada brief agar hasil lebih akurat.
                        </div>

                        <button type="button" wire:click="generateWithAi" wire:loading.attr="disabled" wire:target="generateWithAi" class="w-full rounded-2xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-emerald-700 disabled:cursor-not-allowed disabled:opacity-70">
                            <span wire:loading.remove wire:target="generateWithAi">Buat Draft dengan AI</span>
                            <span wire:loading wire:target="generateWithAi">Menyusun draft berita...</span>
                        </button>
                    </div>
                </div>

                <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50/70 p-4 dark:border-slate-700 dark:bg-slate-950/60">
                    <label class="mb-2 block text-sm font-medium text-slate-700 dark:text-slate-300">Thumbnail</label>
                    <input wire:model="gambar_thumbnail" type="file" accept="image/*" class="w-full text-sm text-slate-500 dark:text-slate-400 file:mr-2 file:rounded-xl file:border-0 file:bg-blue-50 file:px-4 file:py-2.5 file:text-sm file:font-semibold file:text-blue-700 dark:file:bg-blue-900 dark:file:text-blue-300">
                    <p class="mt-2 text-xs text-slate-400">Gunakan gambar horizontal agar kartu berita di halaman publik tampak lebih konsisten.</p>

                    @if ($gambar_thumbnail)
                        <div class="mt-4 overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-700">
                            <img src="{{ $gambar_thumbnail->temporaryUrl() }}" alt="Preview thumbnail" class="h-44 w-full object-cover">
                        </div>
                    @elseif ($existing_thumbnail)
                        <div class="mt-4 overflow-hidden rounded-2xl border border-slate-200 dark:border-slate-700">
                            <img src="{{ Storage::url($existing_thumbnail) }}" alt="Thumbnail saat ini" class="h-44 w-full object-cover">
                        </div>
                    @else
                        <div class="mt-4 rounded-2xl border border-dashed border-slate-300 px-4 py-10 text-center text-sm text-slate-400 dark:border-slate-700 dark:text-slate-500">
                            Belum ada thumbnail dipilih.
                        </div>
                    @endif
                </div>

                <div class="rounded-[1.5rem] border border-slate-200 bg-white p-4 dark:border-slate-700 dark:bg-slate-950/60">
                    <div class="flex items-center justify-between gap-3">
                        <p class="text-xs font-bold uppercase tracking-[0.25em] text-slate-400">Galeri Media Internal</p>
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-[11px] font-semibold text-slate-500 dark:bg-slate-800 dark:text-slate-300">{{ $mediaGallery->count() }} file</span>
                    </div>
                    <p class="mt-2 text-xs text-slate-400">Pilih gambar yang sudah pernah diunggah agar tidak perlu upload ulang ke isi artikel.</p>

                    <div class="mt-4 grid max-h-[28rem] grid-cols-1 gap-3 overflow-y-auto pr-1 sm:grid-cols-2 2xl:grid-cols-1">
                        @forelse($mediaGallery as $media)
                            <div wire:key="media-gallery-{{ md5($media['path']) }}" class="overflow-hidden rounded-2xl border border-slate-200 bg-slate-50 dark:border-slate-700 dark:bg-slate-900">
                                <img src="{{ $media['url'] }}" alt="{{ $media['name'] }}" class="h-28 w-full object-cover">
                                <div class="p-3">
                                    <div class="flex items-center justify-between gap-2">
                                        <p class="truncate text-sm font-semibold text-slate-800 dark:text-white">{{ $media['name'] }}</p>
                                        <span class="rounded-full bg-slate-200 px-2 py-1 text-[10px] font-bold uppercase tracking-[0.18em] text-slate-600 dark:bg-slate-800 dark:text-slate-300">{{ $media['source'] }}</span>
                                    </div>
                                    <p class="mt-1 text-xs text-slate-400">{{ $media['updated_at'] }} • {{ $media['size'] }}</p>
                                    <button type="button" @click="insertUploadedImage('{{ $media['url'] }}')" class="mt-3 w-full rounded-xl bg-blue-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-blue-700">Sisipkan ke Artikel</button>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-2xl border border-dashed border-slate-300 px-4 py-10 text-center text-sm text-slate-400 dark:border-slate-700 dark:text-slate-500 sm:col-span-2 2xl:col-span-1">
                                Belum ada media internal. Upload gambar pertama melalui toolbar editor.
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-[1.5rem] border border-slate-200 bg-white p-4 dark:border-slate-700 dark:bg-slate-950/60">
                    <p class="text-xs font-bold uppercase tracking-[0.25em] text-slate-400">Checklist Editor</p>
                    <div class="mt-3 space-y-3 text-sm text-slate-600 dark:text-slate-300">
                        <label class="flex items-start gap-3">
                            <input type="checkbox" class="mt-1 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                            <span>Judul sudah ringkas dan mudah dipahami.</span>
                        </label>
                        <label class="flex items-start gap-3">
                            <input type="checkbox" class="mt-1 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                            <span>Konten memiliki subjudul atau struktur yang jelas.</span>
                        </label>
                        <label class="flex items-start gap-3">
                            <input type="checkbox" class="mt-1 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                            <span>Gambar pendukung sudah dipilih dari upload baru atau galeri internal.</span>
                        </label>
                    </div>
                </div>
            </aside>
        </div>

        <div class="flex justify-end gap-3 pt-2">
            <a href="{{ route('admin.berita') }}" class="rounded-2xl bg-slate-100 px-5 py-3 text-sm font-medium text-slate-600 transition hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-slate-700">Batal</a>
            <button type="submit" class="rounded-2xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">Simpan Berita</button>
        </div>
    </form>
</section>