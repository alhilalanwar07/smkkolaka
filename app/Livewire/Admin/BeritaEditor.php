<?php

namespace App\Livewire\Admin;

use App\Models\Berita as BeritaModel;
use App\Models\KategoriBerita;
use App\Support\NvidiaNewsGenerator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.admin')]
#[Title('Editor Berita')]
class BeritaEditor extends Component
{
    use WithFileUploads;

    public ?int $editId = null;
    public ?int $sourceId = null;
    public ?string $sourceTitle = null;
    public $kategori_id = '';
    public string $judul = '';
    public string $konten_html = '';
    public string $ai_prompt = '';
    public string $ai_tone = 'formal';
    public string $ai_length = 'sedang';
    public string $status_publikasi = 'draft';
    public $gambar_thumbnail;
    public $inline_image;
    public ?string $existing_thumbnail = null;

    public function mount(?BeritaModel $berita = null): void
    {
        if ($berita?->exists) {
            $this->fillFromArticle($berita, false);

            return;
        }

        $sourceId = request()->integer('source');

        if ($sourceId > 0) {
            $sourceArticle = BeritaModel::findOrFail($sourceId);
            $this->fillFromArticle($sourceArticle, true);
        }
    }

    public function save(): mixed
    {
        $this->validate([
            'judul' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori_berita,id',
            'konten_html' => 'required|string',
            'gambar_thumbnail' => 'nullable|image|max:2048',
        ]);

        $data = [
            'user_id' => auth()->id(),
            'kategori_id' => $this->kategori_id,
            'judul' => $this->judul,
            'slug' => Str::slug($this->judul) . '-' . Str::random(5),
            'konten_html' => $this->konten_html,
            'status_publikasi' => $this->status_publikasi,
            'published_at' => $this->status_publikasi === 'published' ? now() : null,
        ];

        if ($this->gambar_thumbnail) {
            $data['gambar_thumbnail'] = $this->gambar_thumbnail->store('berita', 'public');
        } elseif ($this->existing_thumbnail && ! $this->editId) {
            $data['gambar_thumbnail'] = $this->existing_thumbnail;
        }

        if ($this->editId) {
            $berita = BeritaModel::findOrFail($this->editId);
            unset($data['slug'], $data['user_id']);

            if ($this->status_publikasi === 'published' && ! $berita->published_at) {
                $data['published_at'] = now();
            }

            $berita->update($data);
            $message = 'Berita berhasil diperbarui.';
        } else {
            BeritaModel::create($data);
            $message = 'Berita berhasil disimpan.';
        }

        return redirect()
            ->route('admin.berita')
            ->with('toast', ['type' => 'success', 'message' => $message]);
    }

    public function updatedInlineImage(): void
    {
        $this->validate([
            'inline_image' => 'image|max:4096',
        ]);

        $path = $this->inline_image->store('berita/inline', 'public');

        $this->dispatch('berita-inline-image-uploaded', url: Storage::url($path));
        $this->inline_image = null;
        $this->dispatch('toast', type: 'success', message: 'Gambar berhasil diunggah ke isi artikel.');
    }

    public function generateWithAi(NvidiaNewsGenerator $generator): void
    {
        $validated = $this->validate([
            'ai_prompt' => 'required|string|min:20',
            'ai_tone' => 'required|in:formal,ringan,seremoni',
            'ai_length' => 'required|in:singkat,sedang,panjang',
            'kategori_id' => 'nullable|exists:kategori_berita,id',
        ]);

        try {
            $categoryName = KategoriBerita::query()
                ->whereKey($validated['kategori_id'] ?: null)
                ->value('nama_kategori');

            $article = $generator->generate(
                brief: $validated['ai_prompt'],
                categoryName: $categoryName,
                tone: $validated['ai_tone'],
                length: $validated['ai_length'],
            );
        } catch (Throwable $exception) {
            report($exception);

            $this->addError('ai_prompt', $exception->getMessage());
            $this->dispatch('toast', type: 'error', message: 'Gagal membuat draft berita dengan AI. Periksa brief atau konfigurasi API.');

            return;
        }

        $this->resetValidation('ai_prompt');
        $this->judul = $article['judul'];
        $this->konten_html = $article['konten_html'];

        $this->dispatch('toast', type: 'success', message: 'Draft berita berhasil dibuat dengan AI.');
    }

    public function render()
    {
        return view('livewire.admin.berita-editor', [
            'kategoriList' => KategoriBerita::withCount('berita')->get(),
            'mediaGallery' => $this->buildMediaGallery(),
            'isEditing' => $this->editId !== null,
        ]);
    }

    protected function fillFromArticle(BeritaModel $berita, bool $duplicate = false): void
    {
        $this->editId = $duplicate ? null : $berita->id;
        $this->sourceId = $duplicate ? $berita->id : null;
        $this->sourceTitle = $duplicate ? $berita->judul : null;
        $this->kategori_id = $berita->kategori_id;
        $this->judul = $duplicate ? $berita->judul . ' (Salinan)' : $berita->judul;
        $this->konten_html = $berita->konten_html;
        $this->status_publikasi = $duplicate ? 'draft' : $berita->status_publikasi;
        $this->existing_thumbnail = $berita->gambar_thumbnail;
    }

    protected function buildMediaGallery(): Collection
    {
        $disk = Storage::disk('public');

        return collect(['berita/inline', 'berita'])
            ->flatMap(function (string $directory) use ($disk) {
                return $disk->exists($directory) ? $disk->files($directory) : [];
            })
            ->filter(fn (string $path) => preg_match('/\.(jpg|jpeg|png|gif|webp|svg)$/i', $path) === 1)
            ->unique()
            ->map(function (string $path) use ($disk) {
                $timestamp = $disk->lastModified($path);

                return [
                    'path' => $path,
                    'url' => $disk->url($path),
                    'name' => (string) Str::of(pathinfo($path, PATHINFO_FILENAME))->replace(['-', '_'], ' ')->title(),
                    'size' => number_format(($disk->size($path) ?? 0) / 1024, 1) . ' KB',
                    'updated_at' => date('d M Y H:i', $timestamp),
                    'timestamp' => $timestamp,
                    'source' => str_starts_with($path, 'berita/inline') ? 'Inline' : 'Thumbnail',
                ];
            })
            ->sortByDesc('timestamp')
            ->values()
            ->take(24);
    }
}