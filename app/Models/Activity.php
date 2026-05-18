<?php

namespace App\Models;

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
}
