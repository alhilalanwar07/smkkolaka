<?php

namespace App\Livewire\Frontend;

use App\Models\PpdbApplication;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Daftar Ulang PPDB - SMK Negeri 1 Kolaka')]
class PpdbDaftarUlang extends Component
{
    public string $nomor_pendaftaran = '';
    public string $tanggal_lahir = '';
    public string $catatan_daftar_ulang = '';
    public ?PpdbApplication $result = null;
    public bool $searched = false;
    public bool $submitted = false;

    public function search(): void
    {
        $this->validate([
            'nomor_pendaftaran' => 'required|string',
            'tanggal_lahir' => 'required|date',
        ]);

        $this->searched = true;
        $this->submitted = false;
        $this->result = PpdbApplication::with(['period', 'track', 'programDiterima'])
            ->where('nomor_pendaftaran', $this->nomor_pendaftaran)
            ->whereDate('tanggal_lahir', $this->tanggal_lahir)
            ->first();
    }

    public function submitReRegistration(): void
    {
        $this->validate([
            'nomor_pendaftaran' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'catatan_daftar_ulang' => 'nullable|string|max:1000',
        ]);

        $application = PpdbApplication::with('period')
            ->where('nomor_pendaftaran', $this->nomor_pendaftaran)
            ->whereDate('tanggal_lahir', $this->tanggal_lahir)
            ->firstOrFail();

        if (! $application->period?->isAnnouncementPublished()) {
            $this->addError('nomor_pendaftaran', 'Hasil resmi belum diumumkan. Daftar ulang belum dapat dibuka.');
            return;
        }

        if ($application->hasil_seleksi !== 'passed') {
            $this->addError('nomor_pendaftaran', 'Daftar ulang hanya tersedia untuk peserta yang dinyatakan lulus.');
            return;
        }

        $registrationStartsAt = $application->period->tanggal_mulai_daftar_ulang?->copy()->startOfDay();
        $registrationEndsAt = $application->period->tanggal_selesai_daftar_ulang?->copy()->endOfDay();

        if (! $registrationStartsAt || ! $registrationEndsAt || ! now()->between($registrationStartsAt, $registrationEndsAt)) {
            $this->addError('nomor_pendaftaran', 'Periode daftar ulang saat ini belum dibuka atau sudah berakhir.');
            return;
        }

        $application->update([
            'status_daftar_ulang' => 'submitted',
            'daftar_ulang_at' => now(),
            'catatan_daftar_ulang' => $this->catatan_daftar_ulang,
        ]);

        $this->result = $application->fresh(['period', 'track', 'programDiterima']);
        $this->submitted = true;
        $this->dispatch('toast', type: 'success', message: 'Konfirmasi daftar ulang berhasil dikirim.');
    }

    public function render()
    {
        return view('livewire.frontend.ppdb-daftar-ulang');
    }
}