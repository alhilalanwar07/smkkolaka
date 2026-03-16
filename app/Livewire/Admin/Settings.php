<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.admin')]
#[Title('Pengaturan')]
class Settings extends Component
{
    public bool $showModal = false;
    public ?int $editId = null;

    public string $key = '';
    public string $value = '';
    public string $type = 'string';

    public function create(): void
    {
        $this->editId = null;
        $this->key = '';
        $this->value = '';
        $this->type = 'string';
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $s = Setting::findOrFail($id);
        $this->editId = $s->id;
        $this->key = $s->key;
        $this->value = $s->value ?? '';
        $this->type = $s->type;
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate([
            'key' => 'required|string|max:255',
            'value' => 'nullable|string',
            'type' => 'required|in:string,boolean,json,image',
        ]);

        Setting::updateOrCreate(
            ['id' => $this->editId],
            ['key' => $this->key, 'value' => $this->value, 'type' => $this->type]
        );

        $this->showModal = false;
        $this->dispatch('toast', type: 'success', message: 'Setting berhasil disimpan.');
    }

    public function delete(int $id): void
    {
        Setting::findOrFail($id)->delete();
        $this->dispatch('toast', type: 'success', message: 'Setting dihapus.');
    }

    public function render()
    {
        return view('livewire.admin.settings', [
            'settings' => Setting::orderBy('key')->get(),
        ]);
    }
}
