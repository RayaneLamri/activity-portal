<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    public $guarded = [];

    protected function casts(): array
    {
        return [
            'starts_on' => 'date',
            'ends_on' => 'date',
            'cities' => 'array',
            'period_names' => 'array',
            'age_groups' => 'array',
        ];
    }

    public function cityList(): array
    {
        if (is_array($this->cities) && $this->cities !== []) {
            return $this->cities;
        }

        if (! empty($this->city)) {
            return [$this->city];
        }

        return [];
    }

    public function ageGroupList(): array
    {
        return $this->age_groups ?? [];
    }

    public function periodNameList(): array
    {
        return self::cleanList($this->period_names);
    }

    public function citySummary(): string
    {
        $cities = $this->cityList();

        return $cities === [] ? 'Any city' : implode(', ', $cities);
    }

    public function ageSummary(): string
    {
        $groups = $this->ageGroupList();

        if ($groups === []) {
            return 'Any age';
        }

        $labels = [];

        foreach ($groups as $group) {
            $labels[] = Activity::ageGroupLabelFor($group);
        }

        return implode(', ', $labels);
    }

    public function applyToActivityQuery(Builder $query): void
    {
        $cities = $this->cityList();

        if ($cities !== []) {
            $query->whereIn('city', $cities);
        }

        $ageGroups = $this->ageGroupList();

        if ($ageGroups !== []) {
            $query->whereIn('age_group', $ageGroups);
        }

        $periodNames = $this->periodNameList();

        if ($periodNames !== []) {
            $query->whereIn('period_name', $periodNames);
        }
    }

    public static function cleanList(?array $values): array
    {
        if (! is_array($values)) {
            return [];
        }

        $cleanValues = [];

        foreach ($values as $value) {
            if ($value === null || $value === '') {
                continue;
            }

            $cleanValues[] = $value;
        }

        return $cleanValues;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
