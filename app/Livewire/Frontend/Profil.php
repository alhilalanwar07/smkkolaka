<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\ProfilSekolah;
use App\Models\Pegawai;

#[Layout('components.layouts.app')]
#[Title('Profil Sekolah - SMK Negeri 1 Kolaka')]
class Profil extends Component
{
    public function render()
    {
        $profil = ProfilSekolah::first();
        $pegawai = Pegawai::aktif()->orderBy('nama_lengkap')->get();

        return view('livewire.frontend.profil', compact('profil', 'pegawai'));
    }
}
