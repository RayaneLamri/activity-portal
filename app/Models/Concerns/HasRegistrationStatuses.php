<?php

namespace App\Models\Concerns;

trait HasRegistrationStatuses
{
    public const REQUESTED = 'requested';

    public const INVITED = 'invited';

    public const ACCEPTED = 'accepted';

    public const REJECTED = 'rejected';

    public static function statuses(): array
    {
        return [
            self::REQUESTED,
            self::INVITED,
            self::ACCEPTED,
            self::REJECTED,
        ];
    }

    public static function labelFor(?string $status): string
    {
        return match ($status) {
            self::REQUESTED => 'Requested',
            self::INVITED => 'Invited',
            self::ACCEPTED => 'Accepted',
            self::REJECTED => 'Rejected',
            default => 'Unknown',
        };
    }

    public static function badgeClassFor(?string $status): string
    {
        return match ($status) {
            self::ACCEPTED => 'bg-success',
            self::REJECTED => 'bg-danger',
            self::INVITED => 'bg-info',
            default => 'bg-warning',
        };
    }

    public static function canTransition(?string $fromStatus, string $toStatus): bool
    {
        if ($fromStatus === null) {
            return in_array($toStatus, [self::REQUESTED, self::INVITED], true);
        }

        return in_array($toStatus, match ($fromStatus) {
            self::REQUESTED => [self::ACCEPTED, self::REJECTED],
            self::INVITED => [self::ACCEPTED, self::REJECTED],
            default => [],
        }, true);
    }

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
