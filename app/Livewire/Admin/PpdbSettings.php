<?php

namespace App\Livewire\Admin;

use App\Models\PpdbPeriod;
use App\Models\PpdbQuota;
use App\Models\PpdbTrack;
use App\Support\PpdbPeriodResolver;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('components.layouts.admin')]
#[Title('Pengaturan PPDB')]
class PpdbSettings extends Component
{
    #[Url(as: 'periode')]
    public string $period = '';

    public ?int $managementPeriodId = null;
    public array $periodForm = [];
    public array $trackSettings = [];
    public array $quotaSettings = [];
    public array $newPeriodForm = [];

    public function mount(): void
    {
        $selectedPeriod = $this->resolveSelectedPeriod();

        if ($selectedPeriod && $this->period === '') {
            $this->period = (string) $selectedPeriod->id;
        }

        $this->syncManagementForms($selectedPeriod);
        $this->resetNewPeriodForm($selectedPeriod);
    }

    public function updatedPeriod(): void
    {
        $selectedPeriod = $this->resolveSelectedPeriod();

        $this->syncManagementForms($selectedPeriod);
        $this->resetNewPeriodForm($selectedPeriod);
    }

    public function savePeriodSettings(): void
    {
        if (! $this->managementPeriodId) {
            $this->dispatch('toast', type: 'error', message: 'Belum ada periode PPDB yang bisa diperbarui.');
            return;
        }

        $validated = $this->validate([
            'periodForm.nama_periode' => 'required|string|max:255',
            'periodForm.tahun_ajaran' => 'required|string|max:50',
            'periodForm.tahun_mulai' => 'required|integer|min:2020|max:2100',
            'periodForm.tahun_selesai' => 'required|integer|gte:periodForm.tahun_mulai',
            'periodForm.gelombang_ke' => 'required|integer|min:1|max:20',
            'periodForm.gelombang_label' => 'required|string|max:100',
            'periodForm.tanggal_mulai_pendaftaran' => 'required|date',
            'periodForm.tanggal_selesai_pendaftaran' => 'required|date|after_or_equal:periodForm.tanggal_mulai_pendaftaran',
            'periodForm.tanggal_pengumuman' => 'required|date|after_or_equal:periodForm.tanggal_selesai_pendaftaran',
            'periodForm.tanggal_mulai_daftar_ulang' => 'required|date',
            'periodForm.tanggal_selesai_daftar_ulang' => 'required|date|after_or_equal:periodForm.tanggal_mulai_daftar_ulang',
            'periodForm.status' => 'required|in:draft,published,closed,archived',
            'periodForm.is_active' => 'boolean',
            'periodForm.status_pengumuman' => 'required|in:draft,published',
            'periodForm.catatan_pengumuman' => 'nullable|string',
            'periodForm.deskripsi' => 'nullable|string',
        ]);

        $period = PpdbPeriod::findOrFail($this->managementPeriodId);
        $payload = $validated['periodForm'];
        $payload['is_active'] = (bool) ($payload['is_active'] ?? false);
        $payload['hasil_diumumkan_at'] = $payload['status_pengumuman'] === 'published'
            ? ($period->hasil_diumumkan_at ?? now())
            : null;

        if ($payload['is_active']) {
            PpdbPeriod::query()->whereKeyNot($period->id)->update(['is_active' => false]);
        }

        $period->update($payload);

        $this->syncManagementForms($period->fresh(['tracks', 'quotas.track', 'quotas.programKeahlian']));
        $this->dispatch('toast', type: 'success', message: 'Pengaturan periode PPDB berhasil diperbarui.');
    }

    public function saveTrackSettings(): void
    {
        if (! $this->managementPeriodId) {
            $this->dispatch('toast', type: 'error', message: 'Belum ada periode aktif untuk jalur PPDB.');
            return;
        }

        $this->validate([
            'trackSettings.*.status_tampil' => 'boolean',
            'trackSettings.*.requires_verification' => 'boolean',
            'trackSettings.*.urutan' => 'required|integer|min:1|max:99',
        ]);

        $tracks = PpdbTrack::where('period_id', $this->managementPeriodId)->get();

        foreach ($tracks as $track) {
            if (! isset($this->trackSettings[$track->id])) {
                continue;
            }

            $track->update([
                'status_tampil' => (bool) ($this->trackSettings[$track->id]['status_tampil'] ?? false),
                'requires_verification' => (bool) ($this->trackSettings[$track->id]['requires_verification'] ?? false),
                'urutan' => (int) ($this->trackSettings[$track->id]['urutan'] ?? $track->urutan),
            ]);
        }

        $this->syncManagementForms(PpdbPeriod::with(['tracks', 'quotas.track', 'quotas.programKeahlian'])->find($this->managementPeriodId));
        $this->dispatch('toast', type: 'success', message: 'Pengaturan jalur PPDB berhasil diperbarui.');
    }

    public function saveQuotaSettings(): void
    {
        if (! $this->managementPeriodId) {
            $this->dispatch('toast', type: 'error', message: 'Belum ada periode aktif untuk kuota PPDB.');
            return;
        }

        $this->validate([
            'quotaSettings.*.kuota' => 'required|integer|min:0|max:500',
            'quotaSettings.*.status_aktif' => 'boolean',
        ]);

        $quotas = PpdbQuota::where('period_id', $this->managementPeriodId)->get();

        foreach ($quotas as $quota) {
            if (! isset($this->quotaSettings[$quota->id])) {
                continue;
            }

            $quota->update([
                'kuota' => (int) ($this->quotaSettings[$quota->id]['kuota'] ?? $quota->kuota),
                'status_aktif' => (bool) ($this->quotaSettings[$quota->id]['status_aktif'] ?? false),
            ]);
        }

        $this->syncManagementForms(PpdbPeriod::with(['tracks', 'quotas.track', 'quotas.programKeahlian'])->find($this->managementPeriodId));
        $this->dispatch('toast', type: 'success', message: 'Kuota PPDB berhasil diperbarui.');
    }

    public function createPeriod(): void
    {
        $validated = $this->validate([
            'newPeriodForm.nama_periode' => 'required|string|max:255',
            'newPeriodForm.tahun_ajaran' => 'required|string|max:50',
            'newPeriodForm.tahun_mulai' => 'required|integer|min:2020|max:2100',
            'newPeriodForm.tahun_selesai' => 'required|integer|gte:newPeriodForm.tahun_mulai',
            'newPeriodForm.gelombang_ke' => 'required|integer|min:1|max:20',
            'newPeriodForm.gelombang_label' => 'required|string|max:100',
            'newPeriodForm.tanggal_mulai_pendaftaran' => 'required|date',
            'newPeriodForm.tanggal_selesai_pendaftaran' => 'required|date|after_or_equal:newPeriodForm.tanggal_mulai_pendaftaran',
            'newPeriodForm.tanggal_pengumuman' => 'nullable|date|after_or_equal:newPeriodForm.tanggal_selesai_pendaftaran',
            'newPeriodForm.tanggal_mulai_daftar_ulang' => 'nullable|date',
            'newPeriodForm.tanggal_selesai_daftar_ulang' => 'nullable|date|after_or_equal:newPeriodForm.tanggal_mulai_daftar_ulang',
            'newPeriodForm.status' => 'required|in:draft,published,closed,archived',
            'newPeriodForm.is_active' => 'boolean',
            'newPeriodForm.clone_template' => 'boolean',
            'newPeriodForm.deskripsi' => 'nullable|string',
        ]);

        $payload = $validated['newPeriodForm'];
        $payload['is_active'] = (bool) ($payload['is_active'] ?? false);
        $payload['status_pengumuman'] = 'draft';
        $payload['hasil_diumumkan_at'] = null;
        $payload['catatan_pengumuman'] = null;

        if ($payload['is_active']) {
            PpdbPeriod::query()->update(['is_active' => false]);
        }

        $newPeriod = PpdbPeriod::create($payload);

        if (($validated['newPeriodForm']['clone_template'] ?? false) && $this->managementPeriodId) {
            $templatePeriod = PpdbPeriod::with(['tracks', 'quotas'])->find($this->managementPeriodId);

            if ($templatePeriod) {
                $trackMap = [];

                foreach ($templatePeriod->tracks as $track) {
                    $replica = $track->replicate();
                    $replica->period_id = $newPeriod->id;
                    $replica->save();

                    $trackMap[$track->id] = $replica->id;
                }

                foreach ($templatePeriod->quotas as $quota) {
                    $replica = $quota->replicate();
                    $replica->period_id = $newPeriod->id;
                    $replica->track_id = $quota->track_id ? ($trackMap[$quota->track_id] ?? null) : null;
                    $replica->kuota_terisi = 0;
                    $replica->save();
                }
            }
        }

        $this->period = (string) $newPeriod->id;
        $this->syncManagementForms($newPeriod->fresh(['tracks', 'quotas.track', 'quotas.programKeahlian']));
        $this->resetNewPeriodForm($newPeriod);

        $this->dispatch('toast', type: 'success', message: 'Periode PPDB baru berhasil dibuat.');
    }

    public function activateSelectedPeriod(): void
    {
        if (! $this->managementPeriodId) {
            $this->dispatch('toast', type: 'error', message: 'Belum ada periode yang dipilih untuk diaktifkan.');
            return;
        }

        PpdbPeriod::query()->update(['is_active' => false]);

        $period = PpdbPeriod::findOrFail($this->managementPeriodId);
        $period->update(['is_active' => true]);

        $this->syncManagementForms($period->fresh(['tracks', 'quotas.track', 'quotas.programKeahlian']));

        $this->dispatch('toast', type: 'success', message: 'Periode terpilih sekarang menjadi periode aktif default.');
    }

    public function render()
    {
        $activePeriod = $this->resolveSelectedPeriod();
        $availablePeriods = app(PpdbPeriodResolver::class)->adminOptions();
        $selectedPeriodId = $activePeriod?->id;

        if ($activePeriod && $this->managementPeriodId !== $activePeriod->id) {
            $this->syncManagementForms($activePeriod);
        }

        $quotaOverview = $activePeriod
            ? $activePeriod->quotas->where('status_aktif', true)->values()
            : collect();

        return view('livewire.admin.ppdb-settings', compact('activePeriod', 'quotaOverview', 'availablePeriods', 'selectedPeriodId'));
    }

    protected function resolveSelectedPeriod(): ?PpdbPeriod
    {
        $resolver = app(PpdbPeriodResolver::class);

        return $resolver->resolveAdmin(
            $resolver->resolveInput($this->period),
            ['tracks', 'quotas.track', 'quotas.programKeahlian']
        );
    }

    protected function syncManagementForms(?PpdbPeriod $period): void
    {
        if (! $period) {
            $this->managementPeriodId = null;
            $this->periodForm = [];
            $this->trackSettings = [];
            $this->quotaSettings = [];

            return;
        }

        $this->managementPeriodId = $period->id;
        $this->periodForm = [
            'nama_periode' => $period->nama_periode,
            'tahun_ajaran' => $period->tahun_ajaran,
            'tahun_mulai' => $period->tahun_mulai,
            'tahun_selesai' => $period->tahun_selesai,
            'gelombang_ke' => $period->gelombang_ke,
            'gelombang_label' => $period->gelombang_label,
            'tanggal_mulai_pendaftaran' => $period->tanggal_mulai_pendaftaran?->format('Y-m-d') ?? '',
            'tanggal_selesai_pendaftaran' => $period->tanggal_selesai_pendaftaran?->format('Y-m-d') ?? '',
            'tanggal_pengumuman' => $period->tanggal_pengumuman?->format('Y-m-d') ?? '',
            'tanggal_mulai_daftar_ulang' => $period->tanggal_mulai_daftar_ulang?->format('Y-m-d') ?? '',
            'tanggal_selesai_daftar_ulang' => $period->tanggal_selesai_daftar_ulang?->format('Y-m-d') ?? '',
            'status' => $period->status,
            'is_active' => (bool) $period->is_active,
            'status_pengumuman' => $period->status_pengumuman ?? 'draft',
            'catatan_pengumuman' => $period->catatan_pengumuman ?? '',
            'deskripsi' => $period->deskripsi ?? '',
        ];
        $this->trackSettings = $period->tracks
            ->mapWithKeys(fn (PpdbTrack $track) => [
                $track->id => [
                    'status_tampil' => (bool) $track->status_tampil,
                    'requires_verification' => (bool) $track->requires_verification,
                    'urutan' => (int) $track->urutan,
                ],
            ])
            ->toArray();
        $this->quotaSettings = $period->quotas
            ->mapWithKeys(fn (PpdbQuota $quota) => [
                $quota->id => [
                    'kuota' => (int) $quota->kuota,
                    'status_aktif' => (bool) $quota->status_aktif,
                ],
            ])
            ->toArray();
    }

    protected function resetNewPeriodForm(?PpdbPeriod $period = null): void
    {
        $tahunMulai = $period?->tahun_mulai ?? (int) now()->format('Y');
        $tahunSelesai = $period?->tahun_selesai ?? ($tahunMulai + 1);
        $gelombangKe = $period?->gelombang_ke ?? 1;

        $this->newPeriodForm = [
            'nama_periode' => 'PPDB ' . ($period?->gelombang_label ?? 'Gelombang 1') . ' ' . $tahunMulai . '/' . $tahunSelesai,
            'tahun_ajaran' => $tahunMulai . '/' . $tahunSelesai,
            'tahun_mulai' => $tahunMulai,
            'tahun_selesai' => $tahunSelesai,
            'gelombang_ke' => $gelombangKe,
            'gelombang_label' => $period?->gelombang_label ?? 'Gelombang 1',
            'tanggal_mulai_pendaftaran' => $period?->tanggal_mulai_pendaftaran?->format('Y-m-d') ?? now()->toDateString(),
            'tanggal_selesai_pendaftaran' => $period?->tanggal_selesai_pendaftaran?->format('Y-m-d') ?? now()->addMonth()->toDateString(),
            'tanggal_pengumuman' => $period?->tanggal_pengumuman?->format('Y-m-d') ?? now()->addMonths(2)->toDateString(),
            'tanggal_mulai_daftar_ulang' => $period?->tanggal_mulai_daftar_ulang?->format('Y-m-d') ?? now()->addMonths(2)->addDay()->toDateString(),
            'tanggal_selesai_daftar_ulang' => $period?->tanggal_selesai_daftar_ulang?->format('Y-m-d') ?? now()->addMonths(2)->addWeek()->toDateString(),
            'status' => 'draft',
            'is_active' => false,
            'clone_template' => true,
            'deskripsi' => $period?->deskripsi ?? '',
        ];
    }
}