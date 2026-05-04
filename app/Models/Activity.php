<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Registration;

class Activity extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'starts_on' => 'date',
            'ends_on' => 'date',
            'is_active' => 'boolean',
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
