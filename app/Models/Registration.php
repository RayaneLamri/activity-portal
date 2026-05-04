<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Activity;
use App\Models\User;
use App\Models\RegistrationEvent;
use App\Models\Concerns\HasRegistrationStatuses;

class Registration extends Model
{
    use HasRegistrationStatuses;
    
    public const CREATED_AT = null;
    public const UPDATED_AT = 'date';

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
}
