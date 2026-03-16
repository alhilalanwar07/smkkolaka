<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\GaleriAlbum;

#[Layout('components.layouts.app')]
#[Title('Galeri - SMK Negeri 1 Kolaka')]
class GaleriPage extends Component
{
    public $selectedAlbum = null;

    public function openAlbum($id)
    {
        $this->selectedAlbum = GaleriAlbum::with('items')->find($id);
    }

    public function closeAlbum()
    {
        $this->selectedAlbum = null;
    }

    public function render()
    {
        $albums = GaleriAlbum::withCount('items')->latest()->get();

        return view('livewire.frontend.galeri-page', compact('albums'));
    }
}
