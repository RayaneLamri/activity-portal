<?php

namespace App\Models;

use App\Models\Concerns\HasRegistrationStatuses;
use Illuminate\Database\Eloquent\Model;

class RegistrationEvent extends Model
{
    use HasRegistrationStatuses;

    public const CREATED_AT = 'date';

    public const UPDATED_AT = null;

    public $guarded = [];

    protected function casts(): array
    {
        return [
            'date' => 'datetime',
        ];
    }

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    public function currentStatus(): ?string
    {
        return $this->to_status ?? $this->action;
    }

    public function currentStatusLabel(): string
    {
        return self::labelFor($this->currentStatus());
    }

    public function currentStatusBadgeClass(): string
    {
        return self::badgeClassFor($this->currentStatus());
    }
}
