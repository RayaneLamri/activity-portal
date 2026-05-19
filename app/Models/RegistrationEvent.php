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

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
