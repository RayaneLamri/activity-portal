<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\HasRegistrationStatuses;

class RegistrationEvent extends Model
{
    use HasRegistrationStatuses;
    
    public const CREATED_AT = 'date';
    public const UPDATED_AT = null;

    public $guarded = [];
}