<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    public const AGE_PRESCHOOL = 'preschool';

    public const AGE_LOWER = 'lower';

    public const AGE_UPPER = 'upper';

    public $timestamps = false;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'starts_on' => 'date',
            'ends_on' => 'date',
        ];
    }

    public function ageGroupLabel(): string
    {
        return self::ageGroupLabelFor($this->age_group);
    }

    public static function ageGroups(): array
    {
        return [
            self::AGE_PRESCHOOL => [
                'label' => 'Preschool',
                'min' => 3,
                'max' => 8,
            ],
            self::AGE_LOWER => [
                'label' => 'Lower age group',
                'min' => 7,
                'max' => 15,
            ],
            self::AGE_UPPER => [
                'label' => 'Upper age group',
                'min' => 10,
                'max' => 18,
            ],
        ];
    }

    public static function ageGroupKeys(): array
    {
        return array_keys(self::ageGroups());
    }

    public static function ageGroupLabelFor(?string $key): string
    {
        if ($key === null) {
            return 'Any age';
        }

        $group = self::ageGroups()[$key] ?? null;

        if ($group === null) {
            return 'Any age';
        }

        return "{$group['label']} ({$group['min']}-{$group['max']})";
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    public function acceptedRegistrationsCount()
    {
        return $this->registrations()
            ->where('status', Registration::ACCEPTED)
            ->count();
    }

    public function remainingCapacity()
    {
        return $this->capacity - $this->acceptedRegistrationsCount();
    }

    public function isFull(): bool
    {
        return $this->remainingCapacity() <= 0;
    }
}
