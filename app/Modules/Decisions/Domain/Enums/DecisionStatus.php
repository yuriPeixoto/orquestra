<?php

namespace App\Modules\Decisions\Domain\Enums;

enum DecisionStatus: string
{
    case Proposed = 'proposed';
    case Accepted = 'accepted';
    case Deprecated = 'deprecated';
    case Superseded = 'superseded';
}
