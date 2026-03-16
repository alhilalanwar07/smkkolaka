<?php

namespace App\Livewire\Admin;

use App\Models\Berita as BeritaModel;
use App\Models\KategoriBerita;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
#[Title('Berita')]
class Berita extends Component
{
    use WithPagination;

    public string $tab = 'berita';
    public string $search = '';

    // Kategori
    public string $nama_kategori = '';
    public bool $showKategoriModal = false;
    public ?int $editKategoriId = null;

    public function deleteBerita(int $id): void
    {
        BeritaModel::findOrFail($id)->delete();
        $this->dispatch('toast', type: 'success', message: 'Berita dihapus.');
    }

    public function createKategori(): void { $this->nama_kategori = ''; $this->editKategoriId = null; $this->showKategoriModal = true; }

    public function editKategori(int $id): void
    {
        $k = KategoriBerita::findOrFail($id);
        $this->editKategoriId = $k->id;
        $this->nama_kategori = $k->nama_kategori;
        $this->showKategoriModal = true;
    }

    public function saveKategori(): void
    {
        $this->validate(['nama_kategori' => 'required|string|max:255']);
        KategoriBerita::updateOrCreate(['id' => $this->editKategoriId], [
            'nama_kategori' => $this->nama_kategori,
            'slug' => Str::slug($this->nama_kategori),
        ]);
        $this->showKategoriModal = false;
        $this->dispatch('toast', type: 'success', message: 'Kategori berhasil disimpan.');
    }

    public function deleteKategori(int $id): void
    {
        KategoriBerita::findOrFail($id)->delete();
        $this->dispatch('toast', type: 'success', message: 'Kategori berhasil dihapus.');
    }

    public function updatingSearch(): void { $this->resetPage(); }

    public function render()
    {
        return view('livewire.admin.berita', [
            'beritaList' => BeritaModel::with(['user', 'kategori'])
                ->when($this->search, fn($q) => $q->where('judul', 'like', "%{$this->search}%"))
                ->latest()->paginate(10),
            'kategoriList' => KategoriBerita::withCount('berita')->get(),
        ]);
    }
}
