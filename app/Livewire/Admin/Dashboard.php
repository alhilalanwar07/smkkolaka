<?php

namespace App\Livewire\Admin;

use App\Models\Agenda;
use App\Models\Berita;
use App\Models\GaleriAlbum;
use App\Models\Pegawai;
use App\Models\Pengumuman;
use App\Models\ProgramKeahlian;
use App\Models\TefaProduk;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.admin')]
#[Title('Dashboard')]
class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.admin.dashboard', [
            'stats' => [
                ['label' => 'Pegawai', 'value' => Pegawai::count(), 'color' => 'blue'],
                ['label' => 'Program Keahlian', 'value' => ProgramKeahlian::count(), 'color' => 'indigo'],
                ['label' => 'Produk TEFA', 'value' => TefaProduk::count(), 'color' => 'emerald'],
                ['label' => 'Berita', 'value' => Berita::count(), 'color' => 'orange'],
                ['label' => 'Pengumuman', 'value' => Pengumuman::count(), 'color' => 'pink'],
                ['label' => 'Agenda', 'value' => Agenda::count(), 'color' => 'cyan'],
                ['label' => 'Album Galeri', 'value' => GaleriAlbum::count(), 'color' => 'purple'],
            ],
            'recentBerita' => Berita::with('user')->latest()->take(5)->get(),
            'upcomingAgenda' => Agenda::where('waktu_mulai', '>=', now())->orderBy('waktu_mulai')->take(5)->get(),
        ]);
    }
}
