<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\ProfilSekolah as ProfilSekolahModel;

#[Layout('components.layouts.admin')]
#[Title('Profil Sekolah')]
class ProfilSekolah extends Component
{
    use WithFileUploads;

    public ?int $profilId = null;
    public string $npsn = '';
    public string $nama_sekolah = '';
    public string $alamat_lengkap = '';
    public string $koordinat_peta = '';
    public string $nomor_telepon = '';
    public string $email_resmi = '';
    public string $teks_sambutan_kepsek = '';
    public string $visi_teks = '';
    public string $misi_teks = '';

    public $logo;
    public $favicon;
    public $foto_kepsek;

    public ?string $existing_logo = null;
    public ?string $existing_favicon = null;
    public ?string $existing_foto_kepsek = null;

    // Social media links
    public string $sosmed_facebook = '';
    public string $sosmed_instagram = '';
    public string $sosmed_youtube = '';
    public string $sosmed_tiktok = '';

    public function mount(): void
    {
        $profil = ProfilSekolahModel::first();
        if ($profil) {
            $this->profilId = $profil->id;
            $this->npsn = $profil->npsn ?? '';
            $this->nama_sekolah = $profil->nama_sekolah ?? '';
            $this->alamat_lengkap = $profil->alamat_lengkap ?? '';
            $this->koordinat_peta = $profil->koordinat_peta ?? '';
            $this->nomor_telepon = $profil->nomor_telepon ?? '';
            $this->email_resmi = $profil->email_resmi ?? '';
            $this->teks_sambutan_kepsek = $profil->teks_sambutan_kepsek ?? '';
            $this->visi_teks = $profil->visi_teks ?? '';
            $this->misi_teks = $profil->misi_teks ?? '';
            $this->existing_logo = $profil->logo_path;
            $this->existing_favicon = $profil->favicon_path;
            $this->existing_foto_kepsek = $profil->foto_kepsek;

            $sosmed = $profil->tautan_sosmed ?? [];
            $this->sosmed_facebook = $sosmed['facebook'] ?? '';
            $this->sosmed_instagram = $sosmed['instagram'] ?? '';
            $this->sosmed_youtube = $sosmed['youtube'] ?? '';
            $this->sosmed_tiktok = $sosmed['tiktok'] ?? '';
        }
    }

    public function save(): void
    {
        $this->validate([
            'npsn' => 'required|string|max:20',
            'nama_sekolah' => 'required|string|max:255',
            'alamat_lengkap' => 'required|string',
            'email_resmi' => 'nullable|email',
            'logo' => 'nullable|image|max:2048',
            'favicon' => 'nullable|image|max:1024',
            'foto_kepsek' => 'nullable|image|max:2048',
        ]);

        $data = [
            'npsn' => $this->npsn,
            'nama_sekolah' => $this->nama_sekolah,
            'alamat_lengkap' => $this->alamat_lengkap,
            'koordinat_peta' => $this->koordinat_peta,
            'nomor_telepon' => $this->nomor_telepon,
            'email_resmi' => $this->email_resmi,
            'teks_sambutan_kepsek' => $this->teks_sambutan_kepsek,
            'visi_teks' => $this->visi_teks,
            'misi_teks' => $this->misi_teks,
            'tautan_sosmed' => array_filter([
                'facebook' => $this->sosmed_facebook,
                'instagram' => $this->sosmed_instagram,
                'youtube' => $this->sosmed_youtube,
                'tiktok' => $this->sosmed_tiktok,
            ]),
        ];

        if ($this->logo) {
            $data['logo_path'] = $this->logo->store('profil', 'public');
        }
        if ($this->favicon) {
            $data['favicon_path'] = $this->favicon->store('profil', 'public');
        }
        if ($this->foto_kepsek) {
            $data['foto_kepsek'] = $this->foto_kepsek->store('profil', 'public');
        }

        $profil = ProfilSekolahModel::updateOrCreate(['id' => $this->profilId], $data);
        $this->profilId = $profil->id;

        $this->existing_logo = $profil->logo_path;
        $this->existing_favicon = $profil->favicon_path;
        $this->existing_foto_kepsek = $profil->foto_kepsek;
        $this->reset(['logo', 'favicon', 'foto_kepsek']);

        $this->dispatch('toast', type: 'success', message: 'Profil sekolah berhasil disimpan.');
    }

    public function render()
    {
        return view('livewire.admin.profil-sekolah');
    }
}
