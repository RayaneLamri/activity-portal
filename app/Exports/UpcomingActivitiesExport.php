<?php

namespace App\Exports;

use App\Models\Activity;
use App\Models\Registration;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class UpcomingActivitiesExport implements WithMultipleSheets
{
    private ?Collection $activities = null;

    public function sheets(): array
    {
        return $this->activities()
            ->groupBy('period_name')
            ->map(fn (Collection $activities, ?string $periodName) => new UpcomingActivitiesPeriodSheet(
                $periodName ?: 'No period',
                $activities
            ))
            ->values()
            ->all();
    }

    private function activities(): Collection
    {
        if ($this->activities !== null) {
            return $this->activities;
        }

        return $this->activities = Activity::query()
            ->with([
                'registrations' => fn ($query) => $query
                    ->where('status', Registration::ACCEPTED)
                    ->whereHas('user', fn ($userQuery) => $userQuery->where('is_visible', true))
                    ->with('user')
                    ->orderBy('date'),
            ])
            ->withCount([
                'registrations as accepted_registrations_count' => fn ($query) => $query
                    ->where('status', Registration::ACCEPTED)
                    ->whereHas('user', fn ($userQuery) => $userQuery->where('is_visible', true)),
            ])
            ->whereDate('starts_on', '>=', now()->toDateString())
            ->orderBy('starts_on')
            ->orderBy('title')
            ->get();
    }
}
