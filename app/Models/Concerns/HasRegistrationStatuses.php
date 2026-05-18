<?php

namespace App\Models\Concerns;

trait HasRegistrationStatuses
{
    public const REQUESTED = 'requested';

    public const INVITED = 'invited';

    public const ACCEPTED = 'accepted';

    public const REJECTED = 'rejected';

    public function isRequested(): bool
    {
        return $this->status === self::REQUESTED;
    }

    public function isInvited(): bool
    {
        return $this->status === self::INVITED;
    }

    public function isAccepted(): bool
    {
        return $this->status === self::ACCEPTED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::REJECTED;
    }
}
