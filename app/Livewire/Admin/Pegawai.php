<?php

namespace App\Livewire\Admin;

use App\Models\Pegawai as PegawaiModel;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
#[Title('Pegawai')]
class Pegawai extends Component
{
    use WithPagination, WithFileUploads;

    public bool $showModal = false;
    public ?int $editId = null;
    public string $search = '';

    public string $nip = '';
    public string $nama_lengkap = '';
    public string $jabatan = '';
    public string $bidang_tugas = '';
    public bool $status_aktif = true;
    public $foto_profil;
    public ?string $existing_foto = null;

    public function create(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $pegawai = PegawaiModel::findOrFail($id);
        $this->editId = $pegawai->id;
        $this->nip = $pegawai->nip ?? '';
        $this->nama_lengkap = $pegawai->nama_lengkap;
        $this->jabatan = $pegawai->jabatan ?? '';
        $this->bidang_tugas = $pegawai->bidang_tugas ?? '';
        $this->status_aktif = $pegawai->status_aktif;
        $this->existing_foto = $pegawai->foto_profil;
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nip' => 'nullable|string|max:30',
            'jabatan' => 'nullable|string|max:255',
            'foto_profil' => 'nullable|image|max:2048',
        ]);

        $data = [
            'nip' => $this->nip ?: null,
            'nama_lengkap' => $this->nama_lengkap,
            'jabatan' => $this->jabatan,
            'bidang_tugas' => $this->bidang_tugas,
            'status_aktif' => $this->status_aktif,
        ];

        if ($this->foto_profil) {
            $data['foto_profil'] = $this->foto_profil->store('pegawai', 'public');
        }

        PegawaiModel::updateOrCreate(['id' => $this->editId], $data);

        $this->showModal = false;
        $this->resetForm();
        $this->dispatch('toast', type: 'success', message: $this->editId ? 'Data pegawai diperbarui.' : 'Pegawai berhasil ditambahkan.');
    }

    public function delete(int $id): void
    {
        PegawaiModel::findOrFail($id)->delete();
        $this->dispatch('toast', type: 'success', message: 'Pegawai berhasil dihapus.');
    }

    public function resetForm(): void
    {
        $this->editId = null;
        $this->nip = '';
        $this->nama_lengkap = '';
        $this->jabatan = '';
        $this->bidang_tugas = '';
        $this->status_aktif = true;
        $this->foto_profil = null;
        $this->existing_foto = null;
        $this->resetValidation();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $pegawai = PegawaiModel::when($this->search, fn($q) => $q->where('nama_lengkap', 'like', "%{$this->search}%")->orWhere('nip', 'like', "%{$this->search}%"))
            ->latest()
            ->paginate(10);

        return view('livewire.admin.pegawai', compact('pegawai'));
    }
}
