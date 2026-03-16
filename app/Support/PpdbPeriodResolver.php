<?php

namespace App\Support;

use App\Models\PpdbPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class PpdbPeriodResolver
{
    public function adminOptions(): Collection
    {
        return PpdbPeriod::query()
            ->orderForSelection()
            ->get();
    }

    public function publicOptions(): Collection
    {
        return PpdbPeriod::query()
            ->publiclyVisible()
            ->orderForSelection()
            ->get();
    }

    public function resolveAdmin(?int $periodId = null, array $with = []): ?PpdbPeriod
    {
        $baseQuery = PpdbPeriod::query()->with($with)->orderForSelection();

        if ($periodId) {
            $selected = (clone $baseQuery)->whereKey($periodId)->first();

            if ($selected) {
                return $selected;
            }
        }

        return (clone $baseQuery)->active()->first()
            ?? (clone $baseQuery)->published()->first()
            ?? $baseQuery->first();
    }

    public function resolvePublic(?int $periodId = null, array $with = []): ?PpdbPeriod
    {
        $baseQuery = PpdbPeriod::query()
            ->publiclyVisible()
            ->with($with)
            ->orderForSelection();

        if ($periodId) {
            $selected = (clone $baseQuery)->whereKey($periodId)->first();

            if ($selected) {
                return $selected;
            }
        }

        return (clone $baseQuery)->registrationOpen()->first()
            ?? (clone $baseQuery)->active()->first()
            ?? $baseQuery->first();
    }

    public function resolveInput(mixed $periodId): ?int
    {
        if ($periodId === null || $periodId === '') {
            return null;
        }

        return is_numeric($periodId) ? (int) $periodId : null;
    }

    public function queryPubliclyVisible(): Builder
    {
        return PpdbPeriod::query()->publiclyVisible()->orderForSelection();
    }
}
