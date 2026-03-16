<?php

namespace App\Livewire\Admin;

use App\Models\Agenda as AgendaModel;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
#[Title('Agenda')]
class Agenda extends Component
{
    use WithPagination;

    public bool $showModal = false;
    public ?int $editId = null;
    public string $search = '';

    public string $nama_kegiatan = '';
    public string $deskripsi_kegiatan = '';
    public string $lokasi_pelaksanaan = '';
    public string $waktu_mulai = '';
    public string $waktu_selesai = '';
    public string $kategori_peserta = 'umum';

    public function create(): void { $this->resetForm(); $this->showModal = true; }

    public function edit(int $id): void
    {
        $a = AgendaModel::findOrFail($id);
        $this->editId = $a->id;
        $this->nama_kegiatan = $a->nama_kegiatan;
        $this->deskripsi_kegiatan = $a->deskripsi_kegiatan ?? '';
        $this->lokasi_pelaksanaan = $a->lokasi_pelaksanaan ?? '';
        $this->waktu_mulai = $a->waktu_mulai->format('Y-m-d\TH:i');
        $this->waktu_selesai = $a->waktu_selesai->format('Y-m-d\TH:i');
        $this->kategori_peserta = $a->kategori_peserta;
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'waktu_mulai' => 'required|date',
            'waktu_selesai' => 'required|date|after:waktu_mulai',
        ]);

        $data = [
            'user_id' => auth()->id(),
            'nama_kegiatan' => $this->nama_kegiatan,
            'slug' => Str::slug($this->nama_kegiatan) . '-' . Str::random(5),
            'deskripsi_kegiatan' => $this->deskripsi_kegiatan,
            'lokasi_pelaksanaan' => $this->lokasi_pelaksanaan,
            'waktu_mulai' => $this->waktu_mulai,
            'waktu_selesai' => $this->waktu_selesai,
            'kategori_peserta' => $this->kategori_peserta,
        ];

        if ($this->editId) {
            $item = AgendaModel::findOrFail($this->editId);
            unset($data['slug'], $data['user_id']);
            $item->update($data);
        } else {
            AgendaModel::create($data);
        }

        $this->showModal = false;
        $this->resetForm();
        $this->dispatch('toast', type: 'success', message: 'Agenda berhasil disimpan.');
    }

    public function delete(int $id): void
    {
        AgendaModel::findOrFail($id)->delete();
        $this->dispatch('toast', type: 'success', message: 'Agenda dihapus.');
    }

    public function resetForm(): void
    {
        $this->editId = null;
        $this->nama_kegiatan = '';
        $this->deskripsi_kegiatan = '';
        $this->lokasi_pelaksanaan = '';
        $this->waktu_mulai = '';
        $this->waktu_selesai = '';
        $this->kategori_peserta = 'umum';
        $this->resetValidation();
    }

    public function updatingSearch(): void { $this->resetPage(); }

    public function render()
    {
        return view('livewire.admin.agenda', [
            'items' => AgendaModel::when($this->search, fn($q) => $q->where('nama_kegiatan', 'like', "%{$this->search}%"))
                ->latest('waktu_mulai')->paginate(10),
        ]);
    }
}
