<?php

namespace App\Livewire\Admin;

use App\Models\GaleriAlbum;
use App\Models\GaleriItem;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
#[Title('Galeri')]
class Galeri extends Component
{
    use WithPagination, WithFileUploads;

    public bool $showAlbumModal = false;
    public bool $showItemModal = false;
    public ?int $editAlbumId = null;
    public ?int $activeAlbumId = null;
    public string $search = '';

    // Album fields
    public string $judul_album = '';
    public string $deskripsi_singkat = '';
    public string $tanggal_kegiatan = '';

    // Item fields
    public string $tipe_file = 'foto';
    public $file_path;
    public string $video_url = '';
    public string $caption = '';

    public function createAlbum(): void
    {
        $this->editAlbumId = null;
        $this->judul_album = '';
        $this->deskripsi_singkat = '';
        $this->tanggal_kegiatan = '';
        $this->showAlbumModal = true;
    }

    public function editAlbum(int $id): void
    {
        $a = GaleriAlbum::findOrFail($id);
        $this->editAlbumId = $a->id;
        $this->judul_album = $a->judul_album;
        $this->deskripsi_singkat = $a->deskripsi_singkat ?? '';
        $this->tanggal_kegiatan = $a->tanggal_kegiatan?->format('Y-m-d') ?? '';
        $this->showAlbumModal = true;
    }

    public function saveAlbum(): void
    {
        $this->validate(['judul_album' => 'required|string|max:255']);

        $data = [
            'user_id' => auth()->id(),
            'judul_album' => $this->judul_album,
            'slug' => Str::slug($this->judul_album) . '-' . Str::random(5),
            'deskripsi_singkat' => $this->deskripsi_singkat,
            'tanggal_kegiatan' => $this->tanggal_kegiatan ?: null,
        ];

        if ($this->editAlbumId) {
            $album = GaleriAlbum::findOrFail($this->editAlbumId);
            unset($data['slug'], $data['user_id']);
            $album->update($data);
        } else {
            GaleriAlbum::create($data);
        }

        $this->showAlbumModal = false;
        $this->dispatch('toast', type: 'success', message: 'Album berhasil disimpan.');
    }

    public function deleteAlbum(int $id): void
    {
        GaleriAlbum::findOrFail($id)->delete();
        if ($this->activeAlbumId === $id) {
            $this->activeAlbumId = null;
        }
        $this->dispatch('toast', type: 'success', message: 'Album dihapus.');
    }

    public function openAlbum(int $id): void
    {
        $this->activeAlbumId = $id;
    }

    public function backToAlbums(): void
    {
        $this->activeAlbumId = null;
    }

    // Item CRUD
    public function addItem(): void
    {
        $this->tipe_file = 'foto';
        $this->file_path = null;
        $this->video_url = '';
        $this->caption = '';
        $this->showItemModal = true;
    }

    public function saveItem(): void
    {
        if ($this->tipe_file === 'foto') {
            $this->validate(['file_path' => 'required|image|max:5120']);
            $path = $this->file_path->store('galeri', 'public');
        } else {
            $this->validate(['video_url' => 'required|url']);
            $path = $this->video_url;
        }

        GaleriItem::create([
            'album_id' => $this->activeAlbumId,
            'tipe_file' => $this->tipe_file,
            'file_path' => $path,
            'caption' => $this->caption,
        ]);

        $this->showItemModal = false;
        $this->dispatch('toast', type: 'success', message: 'Item berhasil ditambahkan.');
    }

    public function deleteItem(int $id): void
    {
        GaleriItem::findOrFail($id)->delete();
        $this->dispatch('toast', type: 'success', message: 'Item dihapus.');
    }

    public function updatingSearch(): void { $this->resetPage(); }

    public function render()
    {
        $albums = GaleriAlbum::withCount('items')
            ->when($this->search, fn($q) => $q->where('judul_album', 'like', "%{$this->search}%"))
            ->latest()->paginate(12);

        $activeAlbum = $this->activeAlbumId ? GaleriAlbum::with('items')->find($this->activeAlbumId) : null;

        return view('livewire.admin.galeri', compact('albums', 'activeAlbum'));
    }
}
