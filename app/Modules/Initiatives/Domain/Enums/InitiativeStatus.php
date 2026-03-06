<?php

namespace App\Modules\Initiatives\Domain\Enums;

enum InitiativeStatus: string
{
    case Draft = 'draft';
    case Active = 'active';
    case OnHold = 'on_hold';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
}
