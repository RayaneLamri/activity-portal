<?php

namespace App\Exports;

use App\Models\Activity;
use App\Models\Registration;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

class UpcomingActivitiesExport implements FromCollection, ShouldAutoSize, WithEvents, WithHeadings, WithMapping
{
    private ?Collection $activities = null;

    public function collection(): Collection
    {
        return $this->activities();
    }

    public function headings(): array
    {
        return [
            'Reference',
            'Activity',
            'Start Date',
            'End Date',
            'City',
            'Location',
            'Age Range',
            'Capacity',
            'Accepted',
            'Remaining Spots',
            'Status',
            'Participants',
        ];
    }

    public function map($activity): array
    {
        $acceptedCount = $activity->acceptedRegistrationsCount();
        $capacity = $activity->capacity;
        $remainingCapacity = $activity->remainingCapacity();

        return [
            $activity->external_reference ?: 'N/A',
            $activity->title,
            $activity->starts_on?->format('Y-m-d'),
            $activity->ends_on?->format('Y-m-d'),
            $activity->city ?: 'N/A',
            $activity->location_name,
            $this->formatAgeRange($activity),
            $capacity ?? 'Unlimited',
            $acceptedCount,
            $remainingCapacity ?? 'Unlimited',
            $this->statusLabel($capacity, $acceptedCount),
            $this->participantsLabel($activity),
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event): void {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $this->activities()->count() + 1;

                $sheet->freezePane('A2');
                $sheet->setAutoFilter("A1:L{$lastRow}");
                $sheet->getStyle('A1:L1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => 'solid',
                        'color' => ['rgb' => '1F2937'],
                    ],
                ]);
                $sheet->getStyle("A1:L{$lastRow}")->getAlignment()->setVertical('top');
                $sheet->getStyle("A2:L{$lastRow}")->getAlignment()->setWrapText(true);

                foreach ($this->activities()->values() as $index => $activity) {
                    $row = $index + 2;

                    if ($activity->capacity !== null && $activity->acceptedRegistrationsCount() >= $activity->capacity) {
                        $sheet->getStyle("A{$row}:L{$row}")->applyFromArray([
                            'fill' => [
                                'fillType' => 'solid',
                                'color' => ['rgb' => 'FDE68A'],
                            ],
                        ]);
                    }
                }
            },
        ];
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
                    ->orderBy('created_at'),
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

    private function formatAgeRange(Activity $activity): string
    {
        if ($activity->min_age !== null && $activity->max_age !== null) {
            return "{$activity->min_age}-{$activity->max_age}";
        }

        if ($activity->min_age !== null) {
            return "{$activity->min_age}+";
        }

        if ($activity->max_age !== null) {
            return "Up to {$activity->max_age}";
        }

        return 'N/A';
    }

    private function statusLabel(?int $capacity, int $acceptedCount): string
    {
        if ($capacity === null) {
            return 'Unlimited';
        }

        return $acceptedCount >= $capacity ? 'Full' : 'Open';
    }

    private function participantsLabel(Activity $activity): string
    {
        if ($activity->registrations->isEmpty()) {
            return 'No accepted participants yet';
        }

        return $activity->registrations
            ->map(function ($registration): string {
                $user = $registration->user;

                return "{$user?->name} ({$user?->email})";
            })
            ->implode(' | ');
    }
}
