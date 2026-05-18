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
