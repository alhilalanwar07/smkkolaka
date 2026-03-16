<?php

namespace App\Livewire\Admin;

use App\Models\Pengumuman as PengumumanModel;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
#[Title('Pengumuman')]
class Pengumuman extends Component
{
    use WithPagination, WithFileUploads;

    public bool $showModal = false;
    public ?int $editId = null;
    public string $search = '';

    public string $judul_pengumuman = '';
    public string $isi_pengumuman = '';
    public string $tanggal_mulai_tampil = '';
    public string $tanggal_akhir_tampil = '';
    public $file_lampiran;
    public ?string $existing_lampiran = null;

    public function create(): void { $this->resetForm(); $this->showModal = true; }

    public function edit(int $id): void
    {
        $p = PengumumanModel::findOrFail($id);
        $this->editId = $p->id;
        $this->judul_pengumuman = $p->judul_pengumuman;
        $this->isi_pengumuman = $p->isi_pengumuman;
        $this->tanggal_mulai_tampil = $p->tanggal_mulai_tampil?->format('Y-m-d') ?? '';
        $this->tanggal_akhir_tampil = $p->tanggal_akhir_tampil?->format('Y-m-d') ?? '';
        $this->existing_lampiran = $p->file_lampiran_path;
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate([
            'judul_pengumuman' => 'required|string|max:255',
            'isi_pengumuman' => 'required|string',
            'tanggal_mulai_tampil' => 'nullable|date',
            'tanggal_akhir_tampil' => 'nullable|date|after_or_equal:tanggal_mulai_tampil',
            'file_lampiran' => 'nullable|file|max:5120',
        ]);

        $data = [
            'user_id' => auth()->id(),
            'judul_pengumuman' => $this->judul_pengumuman,
            'slug' => Str::slug($this->judul_pengumuman) . '-' . Str::random(5),
            'isi_pengumuman' => $this->isi_pengumuman,
            'tanggal_mulai_tampil' => $this->tanggal_mulai_tampil ?: null,
            'tanggal_akhir_tampil' => $this->tanggal_akhir_tampil ?: null,
        ];

        if ($this->file_lampiran) {
            $data['file_lampiran_path'] = $this->file_lampiran->store('pengumuman', 'public');
        }

        if ($this->editId) {
            $item = PengumumanModel::findOrFail($this->editId);
            unset($data['slug'], $data['user_id']);
            $item->update($data);
        } else {
            PengumumanModel::create($data);
        }

        $this->showModal = false;
        $this->resetForm();
        $this->dispatch('toast', type: 'success', message: 'Pengumuman berhasil disimpan.');
    }

    public function delete(int $id): void
    {
        PengumumanModel::findOrFail($id)->delete();
        $this->dispatch('toast', type: 'success', message: 'Pengumuman dihapus.');
    }

    public function resetForm(): void
    {
        $this->editId = null;
        $this->judul_pengumuman = '';
        $this->isi_pengumuman = '';
        $this->tanggal_mulai_tampil = '';
        $this->tanggal_akhir_tampil = '';
        $this->file_lampiran = null;
        $this->existing_lampiran = null;
        $this->resetValidation();
    }

    public function updatingSearch(): void { $this->resetPage(); }

    public function render()
    {
        return view('livewire.admin.pengumuman', [
            'items' => PengumumanModel::when($this->search, fn($q) => $q->where('judul_pengumuman', 'like', "%{$this->search}%"))
                ->latest()->paginate(10),
        ]);
    }
}
