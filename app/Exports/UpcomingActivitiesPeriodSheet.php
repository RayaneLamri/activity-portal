<?php

namespace App\Exports;

use App\Models\Activity;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class UpcomingActivitiesPeriodSheet implements FromCollection, ShouldAutoSize, WithEvents, WithHeadings, WithMapping, WithTitle
{
    public function __construct(
        private string $periodName,
        private Collection $activities,
    ) {}

    public function title(): string
    {
        return trim($this->periodName);
    }

    public function collection(): Collection
    {
        return $this->activities;
    }

    public function headings(): array
    {
        return [
            'Reference',
            'Activity',
            'Period',
            'Period Start',
            'Period End',
            'City',
            'Location',
            'Age Group',
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
            $activity->period_name ?? 'N/A',
            $activity->starts_on?->format('d/m/Y'),
            $activity->ends_on?->format('d/m/Y'),
            $activity->city ?: 'N/A',
            $activity->location_name,
            $activity->ageGroupLabel(),
            $capacity,
            $acceptedCount,
            $remainingCapacity,
            $this->statusLabel($capacity, $acceptedCount),
            $this->participantsLabel($activity),
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event): void {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $this->activities->count() + 1;

                $sheet->freezePane('A2');
                $sheet->setAutoFilter("A1:M{$lastRow}");
                $sheet->getStyle('A1:M1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => 'solid',
                        'color' => ['rgb' => '1F2937'],
                    ],
                ]);
                $sheet->getStyle("A1:M{$lastRow}")->getAlignment()->setVertical('top');
                $sheet->getStyle("A2:M{$lastRow}")->getAlignment()->setWrapText(true);

                foreach ($this->activities->values() as $index => $activity) {
                    $row = $index + 2;

                    if ($activity->isFull()) {
                        $sheet->getStyle("A{$row}:M{$row}")->applyFromArray([
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

    private function statusLabel(int $capacity, int $acceptedCount): string
    {
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
