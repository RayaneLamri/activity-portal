<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'starts_on' => 'date',
            'ends_on' => 'date',
        ];
    }

    public function ageLabel(): string
    {
        if ($this->min_age === null && $this->max_age === null) {
            return 'Any age';
        }

        if ($this->min_age === null) {
            return "Up to {$this->max_age}";
        }

        if ($this->max_age === null) {
            return "{$this->min_age}+";
        }

        return "{$this->min_age} - {$this->max_age}";
    }

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->whereRaw(
            'capacity > (
                select count(*)
                from registrations
                where registrations.activity_id = activities.id
                and registrations.status = ?
            )',
            [Registration::ACCEPTED]
        );
    }

    public function scopeUpcoming(Builder $query, ?string $today = null): Builder
    {
        return $query->whereDate('starts_on', '>=', $today ?? now()->toDateString());
    }

    public function scopeWithoutUserRegistration(Builder $query, User $user): Builder
    {
        return $query->whereDoesntHave('registrations', fn ($query) => $query->where('user_id', $user->id));
    }

    public function scopeApplyFilters(Builder $query, array $filters): Builder
    {
        $search = $filters['search'] ?? null;
        $cities = $filters['cities'] ?? [];
        $periodNames = $filters['period_names'] ?? [];
        $minAge = $filters['min_age'] ?? null;
        $maxAge = $filters['max_age'] ?? null;

        return $query
            ->when($search, fn ($query, $search) => $query->whereAny(
                ['title', 'external_reference', 'location_name'],
                'like',
                "%{$search}%"
            ))
            ->when($cities, fn ($query) => $query->whereIn('city', $cities))
            ->when($periodNames, fn ($query) => $query->whereIn('period_name', $periodNames))
            ->when($minAge, fn ($query) => $query->where('max_age', '>=', $minAge))
            ->when($maxAge, fn ($query) => $query->where('min_age', '<=', $maxAge));
    }

    public function scopeApplyPreference(Builder $query, ?UserPreference $preference): Builder
    {
        return $query->applyFilters(self::filtersForPreference($preference));
    }

    public static function filtersForPreference(?UserPreference $preference): array
    {
        if (! $preference) {
            return [
                'cities' => [],
                'period_names' => [],
                'min_age' => null,
                'max_age' => null,
            ];
        }

        [$minAge, $maxAge] = $preference->preferredAgeRange();

        return [
            'cities' => $preference->cities ?: array_filter([$preference->city]),
            'period_names' => $preference->period_names ?? [],
            'min_age' => $minAge,
            'max_age' => $maxAge,
        ];
    }

    public static function cityOptions(Builder $query)
    {
        return $query
            ->whereNotNull('city')
            ->distinct()
            ->orderBy('city')
            ->pluck('city');
    }

    public static function periodOptions(Builder $query)
    {
        return $query
            ->whereNotNull('period_name')
            ->orderBy('starts_on')
            ->pluck('period_name')
            ->unique()
            ->values();
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
