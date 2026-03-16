<?php

namespace App\Support;

use App\Models\PpdbApplication;
use App\Models\PpdbPeriod;
use App\Models\PpdbQuota;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PpdbSelectionService
{
    public function processPeriod(PpdbPeriod $period): array
    {
        return DB::transaction(function () use ($period) {
            /** @var EloquentCollection<int, PpdbApplication> $applications */
            $applications = PpdbApplication::with(['track', 'documents'])
                ->where('period_id', $period->id)
                ->get();

            $quotas = PpdbQuota::where('period_id', $period->id)
                ->where('status_aktif', true)
                ->get();

            foreach ($applications as $application) {
                $components = $this->resolveScoreComponents($application);
                $score = $this->calculateScore($application, $components);
                $eligible = $this->isEligible($application);

                $application->forceFill([
                    'skor_akademik' => $components['skor_akademik'],
                    'skor_prestasi' => $components['skor_prestasi'],
                    'skor_afirmasi' => $components['skor_afirmasi'],
                    'skor_tes_dasar' => $components['skor_tes_dasar'],
                    'skor_wawancara' => $components['skor_wawancara'],
                    'skor_berkas' => $components['skor_berkas'],
                    'skor_seleksi' => $score,
                    'ranking_jalur' => null,
                    'ranking_program' => null,
                    'hasil_seleksi' => $this->initialResult($application, $eligible),
                    'program_diterima_id' => null,
                    'selection_notes' => $this->initialNote($application, $eligible),
                    'status_daftar_ulang' => 'not_available',
                    'daftar_ulang_at' => null,
                    'catatan_daftar_ulang' => null,
                    'verified_daftar_ulang_by' => null,
                    'verified_daftar_ulang_at' => null,
                    'scored_at' => now(),
                ])->save();
            }

            $eligibleApplications = $applications
                ->filter(fn (PpdbApplication $application) => $this->isEligible($application))
                ->values();

            foreach ($eligibleApplications->groupBy('track_id') as $trackApplications) {
                $sortedTrackApplications = $this->sortApplications($trackApplications);

                foreach ($sortedTrackApplications->values() as $rank => $application) {
                    $application->forceFill([
                        'ranking_jalur' => $rank + 1,
                    ])->save();
                }
            }

            $acceptedIds = [];

            foreach ($quotas as $quota) {
                $firstChoicePool = $this->sortApplications($eligibleApplications->filter(function (PpdbApplication $application) use ($quota, $acceptedIds) {
                    return ! in_array($application->id, $acceptedIds, true)
                        && $application->track_id === $quota->track_id
                        && $application->pilihan_program_1_id === $quota->program_keahlian_id;
                }));

                foreach ($firstChoicePool->values() as $rank => $application) {
                    if ($application->ranking_program === null) {
                        $application->forceFill([
                            'ranking_program' => $rank + 1,
                        ])->save();
                    }
                }

                $this->acceptApplications(
                    $firstChoicePool->take($quota->kuota),
                    $quota->track_id,
                    $quota->program_keahlian_id,
                    $acceptedIds,
                    'Lolos pada pilihan utama sesuai kuota dan skor seleksi.'
                );
            }

            foreach ($quotas as $quota) {
                $acceptedCount = count(array_filter(
                    $acceptedIds,
                    fn (array $accepted) => $accepted['track_id'] === $quota->track_id && $accepted['program_id'] === $quota->program_keahlian_id
                ));
                $remainingSeats = max($quota->kuota - $acceptedCount, 0);

                if ($remainingSeats === 0) {
                    continue;
                }

                $secondChoicePool = $this->sortApplications($eligibleApplications->filter(function (PpdbApplication $application) use ($quota, $acceptedIds) {
                    return ! array_key_exists($application->id, $acceptedIds)
                        && $application->track_id === $quota->track_id
                        && $application->pilihan_program_2_id === $quota->program_keahlian_id;
                }));

                foreach ($secondChoicePool->values() as $rank => $application) {
                    if ($application->ranking_program === null) {
                        $application->forceFill([
                            'ranking_program' => $rank + 1,
                        ])->save();
                    }
                }

                $this->acceptApplications(
                    $secondChoicePool->take($remainingSeats),
                    $quota->track_id,
                    $quota->program_keahlian_id,
                    $acceptedIds,
                    'Lolos melalui redistribusi kuota pada pilihan cadangan.'
                );
            }

            foreach ($eligibleApplications as $application) {
                if (array_key_exists($application->id, $acceptedIds)) {
                    continue;
                }

                $application->forceFill([
                    'hasil_seleksi' => 'reserve',
                    'status_pendaftaran' => 'verified',
                    'selection_notes' => 'Masuk daftar cadangan. Menunggu sisa kuota atau penyesuaian hasil akhir panitia.',
                    'program_diterima_id' => null,
                    'status_daftar_ulang' => 'not_available',
                ])->save();
            }

            foreach ($quotas as $quota) {
                $quota->update([
                    'kuota_terisi' => PpdbApplication::query()
                        ->where('period_id', $period->id)
                        ->where('track_id', $quota->track_id)
                        ->where('program_diterima_id', $quota->program_keahlian_id)
                        ->where('hasil_seleksi', 'passed')
                        ->count(),
                ]);
            }

            return [
                'processed' => $applications->count(),
                'eligible' => $eligibleApplications->count(),
                'passed' => count($acceptedIds),
                'reserve' => PpdbApplication::where('period_id', $period->id)->where('hasil_seleksi', 'reserve')->count(),
                'failed' => PpdbApplication::where('period_id', $period->id)->where('hasil_seleksi', 'failed')->count(),
            ];
        });
    }

    protected function calculateScore(PpdbApplication $application, array $components): float
    {
        $weights = $this->weightMap($application->track?->slug);

        $score = 0;

        foreach ($weights as $field => $weight) {
            $score += ($components[$field] ?? 0) * $weight;
        }

        return round($score, 2);
    }

    protected function isEligible(PpdbApplication $application): bool
    {
        return in_array($application->status_berkas, ['complete', 'verified'], true)
            && in_array($application->status_pendaftaran, ['submitted', 'under_review', 'verified', 'accepted'], true)
            && $application->pilihan_program_1_id !== null;
    }

    protected function initialResult(PpdbApplication $application, bool $eligible): string
    {
        if ($application->status_pendaftaran === 'rejected') {
            return 'failed';
        }

        return $eligible ? 'pending' : 'pending';
    }

    protected function initialNote(PpdbApplication $application, bool $eligible): string
    {
        if ($application->status_pendaftaran === 'rejected') {
            return 'Tidak lolos seleksi administratif atau verifikasi panitia.';
        }

        if (! $eligible) {
            return 'Belum masuk pemeringkatan. Lengkapi atau verifikasi berkas terlebih dahulu.';
        }

        return 'Siap diproses pada tahap pemeringkatan dan alokasi kuota.';
    }

    protected function resolveScoreComponents(PpdbApplication $application): array
    {
        $academic = $this->normalizeComponent($application->skor_akademik, (float) ($application->nilai_rata_rata ?? 0));
        $document = $this->normalizeComponent($application->skor_berkas, match ($application->status_berkas) {
            'verified' => 92,
            'complete' => 80,
            'revision' => 55,
            'incomplete' => 45,
            default => 30,
        });
        $baseTest = max(min($academic - 4, 100), 0);
        $interview = $this->normalizeComponent($application->skor_wawancara, max(min($academic - 2, 100), 65));
        $testScore = $this->normalizeComponent($application->skor_tes_dasar, $baseTest);

        return [
            'skor_akademik' => $academic,
            'skor_prestasi' => $this->normalizeComponent($application->skor_prestasi, $application->track?->slug === 'prestasi' ? min($academic + 6, 100) : 0),
            'skor_afirmasi' => $this->normalizeComponent($application->skor_afirmasi, $application->track?->slug === 'afirmasi' ? min($academic + 8, 100) : 0),
            'skor_tes_dasar' => $testScore,
            'skor_wawancara' => $interview,
            'skor_berkas' => $document,
        ];
    }

    protected function normalizeComponent(mixed $value, float $fallback): float
    {
        $resolved = $value === null ? $fallback : (float) $value;

        return round(max(min($resolved, 100), 0), 2);
    }

    protected function weightMap(?string $trackSlug): array
    {
        return match ($trackSlug) {
            'prestasi' => [
                'skor_akademik' => 0.35,
                'skor_prestasi' => 0.30,
                'skor_tes_dasar' => 0.15,
                'skor_berkas' => 0.10,
                'skor_wawancara' => 0.10,
            ],
            'afirmasi' => [
                'skor_akademik' => 0.30,
                'skor_afirmasi' => 0.30,
                'skor_berkas' => 0.20,
                'skor_wawancara' => 0.10,
                'skor_tes_dasar' => 0.10,
            ],
            default => [
                'skor_akademik' => 0.55,
                'skor_tes_dasar' => 0.20,
                'skor_berkas' => 0.15,
                'skor_wawancara' => 0.10,
            ],
        };
    }

    protected function sortApplications(Collection $applications): Collection
    {
        return $applications
            ->sort(function (PpdbApplication $left, PpdbApplication $right) {
                $leftScore = (float) ($left->skor_seleksi ?? 0);
                $rightScore = (float) ($right->skor_seleksi ?? 0);

                if ($leftScore !== $rightScore) {
                    return $rightScore <=> $leftScore;
                }

                $leftSubmittedAt = $left->submitted_at?->timestamp ?? $left->created_at?->timestamp ?? 0;
                $rightSubmittedAt = $right->submitted_at?->timestamp ?? $right->created_at?->timestamp ?? 0;

                if ($leftSubmittedAt !== $rightSubmittedAt) {
                    return $leftSubmittedAt <=> $rightSubmittedAt;
                }

                return $left->id <=> $right->id;
            })
            ->values();
    }

    protected function acceptApplications(Collection $applications, int $trackId, int $programId, array &$acceptedIds, string $note): void
    {
        foreach ($applications as $application) {
            $application->forceFill([
                'hasil_seleksi' => 'passed',
                'status_pendaftaran' => 'accepted',
                'program_diterima_id' => $programId,
                'selection_notes' => $note,
                'status_daftar_ulang' => 'pending',
                'verified_at' => $application->verified_at ?? now(),
            ])->save();

            $acceptedIds[$application->id] = [
                'track_id' => $trackId,
                'program_id' => $programId,
            ];
        }
    }
}