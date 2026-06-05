<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    public const AGE_PRESCHOOL = 'preschool';

    public const AGE_LOWER = 'lower';

    public const AGE_UPPER = 'upper';

    public $guarded = [];

    protected function casts(): array
    {
        return [
            'cities' => 'array',
            'period_names' => 'array',
            'age_groups' => 'array',
        ];
    }

    public function preferredAgeRange(): array
    {
        if ($this->min_age !== null || $this->max_age !== null) {
            return [$this->min_age, $this->max_age];
        }

        return self::ageRangeForGroups($this->age_groups);
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

    public static function ageRangeForGroups(?array $groups): array
    {
        $minAge = null;
        $maxAge = null;

        foreach ($groups ?? [] as $groupKey) {
            $group = self::ageGroups()[$groupKey] ?? null;

            if ($group === null) {
                continue;
            }

            $minAge = $minAge === null ? $group['min'] : min($minAge, $group['min']);
            $maxAge = $maxAge === null ? $group['max'] : max($maxAge, $group['max']);
        }

        return [$minAge, $maxAge];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
