<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\ProgramKeahlian;

#[Layout('components.layouts.app')]
#[Title('Program Keahlian - SMK Negeri 1 Kolaka')]
class JurusanIndex extends Component
{
    public function render()
    {
        $jurusans = ProgramKeahlian::tampil()->get();

        return view('livewire.frontend.jurusan-index', compact('jurusans'));
    }
}
