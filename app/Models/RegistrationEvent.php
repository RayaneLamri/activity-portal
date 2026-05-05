<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\HasRegistrationStatuses;
use App\Models\RegistrationEvent;
use App\Models\User;

class RegistrationEvent extends Model
{
    use HasRegistrationStatuses;
    
    public const CREATED_AT = 'date';
    public const UPDATED_AT = null;

    public $guarded = [];

    public function events()
    {
        return $this->hasMany(RegistrationEvent::class)->orderByDesc('date');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}