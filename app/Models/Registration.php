<?php

namespace App\Models;

use App\Models\Concerns\HasRegistrationStatuses;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasRegistrationStatuses;

    public const CREATED_AT = null;

    public const UPDATED_AT = 'date';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'date' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function events()
    {
        return $this->hasMany(RegistrationEvent::class);
    }

    public function statusLabel(): string
    {
        return self::labelFor($this->status);
    }

    public function statusBadgeClass(): string
    {
        return self::badgeClassFor($this->status);
    }
}
