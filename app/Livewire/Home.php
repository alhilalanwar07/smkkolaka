<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\ProfilSekolah;
use App\Models\ProgramKeahlian;
use App\Models\Berita;
use App\Models\Agenda;
use App\Models\Pengumuman;
use App\Models\Pegawai;
use App\Models\GaleriAlbum;
use App\Models\GaleriItem;
use App\Models\TefaProduk;

#[Layout('components.layouts.app')]
#[Title('Beranda - SMK Negeri 1 Kolaka')]
class Home extends Component
{
    public function render()
    {
        $profil = ProfilSekolah::first();
        $jurusans = ProgramKeahlian::tampil()->get();
        $recentBerita = Berita::published()->with('kategori')->latest('published_at')->take(6)->get();
        $upcomingAgenda = Agenda::upcoming()->take(8)->get();
        $pengumuman = Pengumuman::aktif()->latest()->take(5)->get();
        $galeriItems = GaleriItem::where('tipe_file', 'foto')->latest()->take(12)->get();
        $tefaProduks = TefaProduk::where('status_ketersediaan', 'tersedia')->latest()->take(8)->get();
        $pegawaiHighlight = Pegawai::aktif()->take(12)->get();

        $stats = [
            ['label' => 'Program Keahlian', 'value' => ProgramKeahlian::tampil()->count(), 'icon' => 'academic', 'color' => 'from-blue-400 to-blue-600'],
            ['label' => 'Tenaga Pengajar', 'value' => Pegawai::aktif()->count(), 'icon' => 'users', 'color' => 'from-indigo-400 to-indigo-600'],
            ['label' => 'Berita Diterbitkan', 'value' => Berita::published()->count(), 'icon' => 'newspaper', 'color' => 'from-purple-400 to-purple-600'],
            ['label' => 'Album Galeri', 'value' => GaleriAlbum::count(), 'icon' => 'camera', 'color' => 'from-cyan-400 to-cyan-600'],
            ['label' => 'Produk TEFA', 'value' => TefaProduk::count(), 'icon' => 'cube', 'color' => 'from-emerald-400 to-emerald-600'],
            ['label' => 'Agenda Kegiatan', 'value' => Agenda::count(), 'icon' => 'calendar', 'color' => 'from-amber-400 to-amber-600'],
        ];

        return view('livewire.home', compact(
            'profil', 'jurusans', 'recentBerita', 'upcomingAgenda',
            'pengumuman', 'stats', 'galeriItems', 'tefaProduks', 'pegawaiHighlight'
        ));
    }
}
