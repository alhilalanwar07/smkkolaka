<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\ProgramKeahlian;

#[Layout('components.layouts.app')]
class JurusanDetail extends Component
{
    public ProgramKeahlian $jurusan;

    public function mount(string $slug)
    {
        $this->jurusan = ProgramKeahlian::where('slug', $slug)->where('status_tampil', true)->firstOrFail();
    }

    public function render()
    {
        return view('livewire.frontend.jurusan-detail')
            ->title($this->jurusan->nama_jurusan . ' - SMK Negeri 1 Kolaka');
    }
}
