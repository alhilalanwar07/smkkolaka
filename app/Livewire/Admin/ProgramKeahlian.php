<?php

namespace App\Livewire\Admin;

use App\Models\ProgramKeahlian as ProgramKeahlianModel;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
#[Title('Program Keahlian')]
class ProgramKeahlian extends Component
{
    use WithPagination, WithFileUploads;

    public bool $showModal = false;
    public ?int $editId = null;
    public string $search = '';

    public string $kode_jurusan = '';
    public string $nama_jurusan = '';
    public string $deskripsi_lengkap = '';
    public string $fasilitas_unggulan = '';
    public string $prospek_karir = '';
    public bool $status_tampil = true;
    public $gambar_cover;
    public ?string $existing_cover = null;

    public function create(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $item = ProgramKeahlianModel::findOrFail($id);
        $this->editId = $item->id;
        $this->kode_jurusan = $item->kode_jurusan;
        $this->nama_jurusan = $item->nama_jurusan;
        $this->deskripsi_lengkap = $item->deskripsi_lengkap ?? '';
        $this->fasilitas_unggulan = $item->fasilitas_unggulan ?? '';
        $this->prospek_karir = $item->prospek_karir ?? '';
        $this->status_tampil = $item->status_tampil;
        $this->existing_cover = $item->gambar_cover;
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate([
            'kode_jurusan' => 'required|string|max:20',
            'nama_jurusan' => 'required|string|max:255',
            'gambar_cover' => 'nullable|image|max:2048',
        ]);

        $data = [
            'kode_jurusan' => $this->kode_jurusan,
            'nama_jurusan' => $this->nama_jurusan,
            'slug' => Str::slug($this->nama_jurusan),
            'deskripsi_lengkap' => $this->deskripsi_lengkap,
            'fasilitas_unggulan' => $this->fasilitas_unggulan,
            'prospek_karir' => $this->prospek_karir,
            'status_tampil' => $this->status_tampil,
        ];

        if ($this->gambar_cover) {
            $data['gambar_cover'] = $this->gambar_cover->store('program-keahlian', 'public');
        }

        ProgramKeahlianModel::updateOrCreate(['id' => $this->editId], $data);
        $this->showModal = false;
        $this->resetForm();
        $this->dispatch('toast', type: 'success', message: 'Program keahlian berhasil disimpan.');
    }

    public function delete(int $id): void
    {
        ProgramKeahlianModel::findOrFail($id)->delete();
        $this->dispatch('toast', type: 'success', message: 'Program keahlian berhasil dihapus.');
    }

    public function resetForm(): void
    {
        $this->editId = null;
        $this->kode_jurusan = '';
        $this->nama_jurusan = '';
        $this->deskripsi_lengkap = '';
        $this->fasilitas_unggulan = '';
        $this->prospek_karir = '';
        $this->status_tampil = true;
        $this->gambar_cover = null;
        $this->existing_cover = null;
        $this->resetValidation();
    }

    public function updatingSearch(): void { $this->resetPage(); }

    public function render()
    {
        return view('livewire.admin.program-keahlian', [
            'items' => ProgramKeahlianModel::when($this->search, fn($q) => $q->where('nama_jurusan', 'like', "%{$this->search}%"))->latest()->paginate(10),
        ]);
    }
}
