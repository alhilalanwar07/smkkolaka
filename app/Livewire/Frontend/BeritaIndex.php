<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithPagination;
use App\Models\Berita;
use App\Models\KategoriBerita;

#[Layout('components.layouts.app')]
#[Title('Berita - SMK Negeri 1 Kolaka')]
class BeritaIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $kategoriFilter = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingKategoriFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Berita::published()->with('kategori')->latest('published_at');

        if ($this->search) {
            $query->where('judul', 'like', '%' . $this->search . '%');
        }

        if ($this->kategoriFilter) {
            $query->where('kategori_id', $this->kategoriFilter);
        }

        $berita = $query->paginate(9);
        $kategoris = KategoriBerita::orderBy('nama_kategori')->get();

        return view('livewire.frontend.berita-index', compact('berita', 'kategoris'));
    }
}
