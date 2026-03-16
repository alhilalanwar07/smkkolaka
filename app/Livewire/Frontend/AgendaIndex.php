<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithPagination;
use App\Models\Agenda;

#[Layout('components.layouts.app')]
#[Title('Agenda - SMK Negeri 1 Kolaka')]
class AgendaIndex extends Component
{
    use WithPagination;

    public $filter = 'upcoming'; // upcoming, past, all

    public function updatingFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Agenda::query();

        if ($this->filter === 'upcoming') {
            $query->where('waktu_mulai', '>=', now())->orderBy('waktu_mulai');
        } elseif ($this->filter === 'past') {
            $query->where('waktu_mulai', '<', now())->latest('waktu_mulai');
        } else {
            $query->latest('waktu_mulai');
        }

        $agendas = $query->paginate(12);

        return view('livewire.frontend.agenda-index', compact('agendas'));
    }
}
