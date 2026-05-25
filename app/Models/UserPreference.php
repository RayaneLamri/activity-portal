<?php

namespace App\Models;

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
        return $this->cities ?? array_values(array_filter([$this->city]));
    }

    public function ageGroupList(): array
    {
        return $this->age_groups ?? [];
    }

    public function periodNameList(): array
    {
        return array_values(array_filter($this->period_names ?? []));
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

        return collect($groups)
            ->map(fn (string $group) => Activity::ageGroupLabelFor($group))
            ->implode(', ');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
